<?php

namespace ASoftwareHouse\EParagony;

use ASoftwareHouse\EParagony\Entity\EparagonyDocumentStatus;
use DateTime;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use PrestaShopLogger;

class ReceiptQueueManager
{
    const COUNT_IGNORE = 'ignore';
    const COUNT_ZERO = 'zero';
    const COUNT_RESET = 'reset';
    const COUNT_INCREMENT = 'increment';

    const MAX_RETRY = 10;

    private $em;

    public function __construct(
        EntityManagerInterface $em
    ) {
        $this->em = $em;
    }

    private function addTransition(
        EparagonyDocumentStatus $status,
        DateTime $now,
        string $state,
        string $count
    ) {
        $transition = $now->getTimestamp() . ' ' . $state;
        switch ($count) {
            case self::COUNT_IGNORE:
                $count = null;
                break;
            case self::COUNT_ZERO:
                $count = 0;
                $status->setRetryCount(0);
                break;
            case self::COUNT_RESET:
                $count = 1;
                $status->setRetryCount(1);
                break;
            case self::COUNT_INCREMENT:
            default:
                $count = $status->getRetryCount();
                $count = max(0, $count);
                $count++;
                $status->setRetryCount($count);
        }
        if ($count) {
            $transition .= ' (' . $count . ')';
        }
        $status
            ->appendTransition($transition)
            ->setUpdated($now)
            ->setChecked($now)
            ->setDocumentState($state)
        ;

        return $count;
    }

    public function createEmpty($orderId)
    {
            $now = new DateTime();
            $randomId = $now->format('ym') . '-' . bin2hex(random_bytes(8));
            $status = new EparagonyDocumentStatus();
            $status
                ->setDocumentType($status::TYPE_RECEIPT)
                ->setTextId($randomId)
                ->setOrderId($orderId)
                ->setRest('{}')
            ;
            $this->addTransition($status, $now, $status::STATE_QUEUED, self::COUNT_ZERO);
            $this->em->persist($status);
            $this->em->flush();

        return $status;
    }

    /**
     * Force queued status.
     *
     * This function ignore existing status.
     * Do not use in normal operations.
     *
     * @param EparagonyDocumentStatus $status
     */
    public function forceQueued(EparagonyDocumentStatus $status)
    {
        $state = $status->getDocumentState();
        if ($status::STATE_QUEUED !== $state) {
            $now = new DateTime();
            $randomId = $now->format('ym') . '-' . bin2hex(random_bytes(8)) . '_forced';
            $status->setTextId($randomId);
            $this->addTransition($status, $now, $status::STATE_QUEUED, self::COUNT_RESET);
            $this->em->flush();
        }
    }

    public function prepForDownload(EparagonyDocumentStatus $status)
    {
        $state = $status->getDocumentState();
        $now = new DateTime();
        if ($status::STATE_QUEUED === $state) {
            $this->addTransition($status, $now, $status::STATE_DOWNLOADING, self::COUNT_RESET);
            $this->em->flush();
            return true;
        } elseif (EparagonyDocumentStatus::STATE_DOWNLOADING === $state) {
            $timeLimit = new DateTime('10 minutes ago');
            if ($status->getUpdated() > $timeLimit) {
                /* It is expected this state will be changed from different thread in few seconds. */
                $status->setChecked($now);
                $this->em->flush();
                return false;
            } else {
                $count = $status->getRetryCount();
                if ($count >= self::MAX_RETRY) {
                    $this->addTransition($status, $now, $status::STATE_ERROR, self::COUNT_IGNORE);
                    $this->em->flush();
                    return false;
                } else {
                    $this->addTransition($status, $now, $status::STATE_DOWNLOADING, self::COUNT_INCREMENT);
                    $this->em->flush();
                    return true;
                }
            }
        } else {
            /* Nothing sane to do. */
            return false;
        }
    }

    public function finishDownload(EparagonyDocumentStatus $status)
    {
        $now = new DateTime();
        $state = $status->getDocumentState();
        if ($status::STATE_DOWNLOADING === $state) {
            $this->addTransition($status, $now, $status::STATE_COMPILED, self::COUNT_ZERO);
        } else {
            $this->addTransition($status, $now, $status::STATE_UNKNOWN, self::COUNT_IGNORE);
        }
        $this->em->flush();
    }

    /**
     * @return EparagonyDocumentStatus|null
     */
    public function getFromQueue()
    {
        $now = new DateTime();
        $qb = $this->em->createQueryBuilder();
        $qb
            ->select('ds')
            ->from(EparagonyDocumentStatus::class, 'ds')
            ->andWhere($qb->expr()->orX(
                'ds.documentState = :state_compiled',
                'ds.documentState = :state_downloading',
                'ds.documentState = :state_in_progress',
                'ds.documentState = :state_queued'
            ))
            ->andWhere('ds.checked < :now')
            ->setParameter('state_compiled', EparagonyDocumentStatus::STATE_COMPILED)
            ->setParameter('state_downloading', EparagonyDocumentStatus::STATE_DOWNLOADING)
            ->setParameter('state_in_progress', EparagonyDocumentStatus::STATE_IN_PROGRESS)
            ->setParameter('state_queued', EparagonyDocumentStatus::STATE_QUEUED)
            ->setParameter('now', $now)
            ->addOrderBy('ds.checked', 'ASC')
            ->setMaxResults(1)
        ;
        $one = $qb->getQuery()->getOneOrNullResult();
        if (!$one) {
            return null;
        }
        assert($one instanceof EparagonyDocumentStatus);
        /* Update to protect from infinite loop in case of error. */
        $one->setChecked($now);
        $this->em->flush();

        return $one;
    }

    public function needDownloadStep(EparagonyDocumentStatus $status)
    {
        $state = $status->getDocumentState();
        switch ($state) {
            case $status::STATE_QUEUED:
            case $status::STATE_DOWNLOADING:
                return true;
            default:
                return false;
        }
    }

    public function prepForUpload(EparagonyDocumentStatus $status)
    {
        $state = $status->getDocumentState();
        $now = new DateTime();
        switch ($state) {
            case $status::STATE_COMPILED:
                $this->addTransition($status, $now, $status::STATE_IN_PROGRESS, self::COUNT_RESET);
                $this->em->flush();
                return true;
            case $status::STATE_IN_PROGRESS:
                $count = $status->getRetryCount();
                if ($count >= self::MAX_RETRY) {
                    $this->addTransition($status, $now, $status::STATE_UNKNOWN, self::COUNT_ZERO);
                    $this->em->flush();
                    return false;
                } else {
                    $this->addTransition($status, $now, $status::STATE_IN_PROGRESS, self::COUNT_INCREMENT);
                    $oneHour = new DateTime('1 hour'); #TODO This should be possible to be configured.
                    /* Mark check in the future. */
                    $status->setChecked($oneHour);
                    $this->em->flush();
                    return true;
                }
            case $status::STATE_READY:
            case $status::STATE_CONFIRMED:
                /* The document is finished. Do not alter state. */
                return false;
            default:
                $this->addTransition($status, $now, $status::STATE_ERROR, self::COUNT_IGNORE);
                $this->em->flush();
                return false;
        }
    }

    public function finishQueue(EparagonyDocumentStatus $status)
    {
        $state = $status->getDocumentState();
        $now = new DateTime();
        switch ($state) {
            case $status::STATE_IN_PROGRESS:
                $this->addTransition($status, $now, $status::STATE_READY, self::COUNT_ZERO);
                $this->em->flush();
                return true;
            case $status::STATE_COMPILED:
                $this->addTransition($status, $now, $status::STATE_UNKNOWN, self::COUNT_IGNORE);
                $this->em->flush();
                return false;
            case $status::STATE_READY:
            case $status::STATE_CONFIRMED:
                /* Nothing to do. */
                return true;
            default:
                $this->addTransition($status, $now, $status::STATE_ERROR, self::COUNT_IGNORE);
                $this->em->flush();
                return false;
        }
    }

    public function repeatQueue(EparagonyDocumentStatus $status)
    {
        $state = $status->getDocumentState();
        $now = new DateTime();
        switch ($state) {
            case $status::STATE_IN_PROGRESS:
                /* Nothing to do. It should be moved to end already. */
                return true;
            case $status::STATE_READY:
            case $status::STATE_CONFIRMED:
                /* Nothing to do. */
                return true;
            default:
                $this->addTransition($status, $now, $status::STATE_UNKNOWN, self::COUNT_IGNORE);
                $this->em->flush();
                return false;
        }
    }

    public function confirmQueue(EparagonyDocumentStatus $status)
    {
        $state = $status->getDocumentState();
        $now = new DateTime();
        switch ($state) {
            case $status::STATE_READY:
            case $status::STATE_IN_PROGRESS:
                $this->addTransition($status, $now, $status::STATE_CONFIRMED, self::COUNT_ZERO);
                $this->em->flush();
                return true;
            case $status::STATE_COMPILED:
                $this->addTransition($status, $now, $status::STATE_UNKNOWN, self::COUNT_IGNORE);
                $this->em->flush();
                return false;
            case $status::STATE_CONFIRMED:
                /* Nothing to do. */
                return true;
            default:
                $this->addTransition($status, $now, $status::STATE_ERROR, self::COUNT_IGNORE);
                $this->em->flush();
                return false;
        }
    }

    public function cancelQueue(EparagonyDocumentStatus $status)
    {
        $state = $status->getDocumentState();
        $now = new DateTime();
        switch ($state) {
            case $status::STATE_COMPILED:
            case $status::STATE_QUEUED:
                $this->addTransition($status, $now, $status::STATE_UNKNOWN, self::COUNT_IGNORE);
                $this->em->flush();
                return true;
            case $status::STATE_READY:
            case $status::STATE_CONFIRMED:
                /* Nothing to do. Ignore command. */
                return true;
            default:
                $this->addTransition($status, $now, $status::STATE_ERROR, self::COUNT_IGNORE);
                $this->em->flush();
                return true;
        }
    }

}
