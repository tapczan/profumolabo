<?php
/**
 * @author Check AUTHORS file.
 * @copyright Spark
 * @license proprietary
 */

namespace Spark\EParagony;

use DateTime;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use OrderHistory;
use PrestaShop\PrestaShop\Adapter\Entity\Order;
use PrestaShop\PrestaShop\Adapter\Entity\OrderState;
use PrestaShopLogger;
use Spark\EParagony\Entity\EparagonyDocumentStatus;
use Spark\EParagony\Repository\EparagonyDocumentStatusRepository;
use Spark\EParagony\SparkApi\ApiSpark;
use Spark\EParagony\SparkApi\ApiSparkException;
use Spark\EParagony\SparkApi\ApiSparkFactory;

/*

graph LR
    Blank([Blank]) --> Queued
    Sent -->|potwierdzenie od serwera| Confirmed
    Sent -->|błąd z serwera| Error
    Queued -->|przygotowanie do wysyłki| Sending
    Sending -->|połączenie z API wykonane| Sent
    Sending -->|przygotowanie do wysyłki udane| Sending
    Sending -->|przygotowanie do wysyłki nieudane| Error
    Confirmed -->|błąd z serwera| Confirmed
    Confirmed -->|potwierdzenie od serwera| Confirmed
    Blank & Queued & Sending -.->|potwierdzenie od serwera| Unknown
    Blank & Queued & Sending -.->|błąd z serwera| Unknown

*/

class DocumentsManager extends MiniDocumentsManager
{
    private $mailer;
    private $apiSparkFactory;
    private $apiSpark;

    const MAX_RETRY = 10;

    public function __construct(
        EntityManagerInterface $em,
        Mailer $mailer,
        ApiSparkFactory $apiSparkFactory,
        OrderChecker $orderChecker
    ) {
        parent::__construct($em, $orderChecker);

        $this->mailer = $mailer;
        $this->apiSparkFactory = $apiSparkFactory;
    }

    public function handleHistoryChange(OrderHistory $orderHistory)
    {
        $documentStatus = parent::handleHistoryChange($orderHistory);

        if ($documentStatus) {
            /* We can assume the method below is not thrown. */
            $this->downloadRecipe($documentStatus);
        }

        return $documentStatus;
    }

    public function getByTag($tag) : ?EparagonyDocumentStatus
    {
        $repo = $this->em->getRepository(EparagonyDocumentStatus::class);
        $one = $repo->findOneBy(['textId' => $tag]);
        assert(!$one | $one instanceof EparagonyDocumentStatus);

        return $one;
    }

    public function confirmQueue(EparagonyDocumentStatus $documentStatus)
    {
        $rapportError = false;
        try {
            $state = $documentStatus->getDocumentState();
            $now = new DateTime();
            switch ($state) {
                case $documentStatus::STATE_SENT:
                    $this->addTransition($documentStatus, $now, $documentStatus::STATE_CONFIRMED, self::COUNT_ZERO);
                    $this->em->flush();
                    break;
                case $documentStatus::STATE_CONFIRMED:
                    /* Nothing to do. */
                    break;
                default:
                    /* Not expected. */
                    $this->addTransition($documentStatus, $now, $documentStatus::STATE_UNKNOWN, self::COUNT_IGNORE);
                    $this->em->flush();
                    break;
            }
        } catch (ORMException|DBALException $ex) {
            #TODO Check if this logger is reliable in this context.
            /* It is not critical. */
            $level = self::LOG_SEVERITY_LEVEL_WARNING;
            PrestaShopLogger::addLog(
                $ex->getMessage(),
                $level,
                $ex->getCode()
            );

            $rapportError = true;
        }

        if ($rapportError) {
            throw new DatabaseError('Cannot finish queue.');
        } else {
            /* Send after flush. */
            $this->mailer->mailReceipt($documentStatus);
        }
    }

    public function cancelQueue(EparagonyDocumentStatus $documentStatus)
    {
        $rapportError = false;
        try {
            $state = $documentStatus->getDocumentState();
            $now = new DateTime();
            switch ($state) {
                case $documentStatus::STATE_CONFIRMED:
                    /* Nothing to do. Ignore command. */
                    break;
                case $documentStatus::STATE_SENT:
                    $this->addTransition($documentStatus, $now, $documentStatus::STATE_ERROR, self::COUNT_IGNORE);
                    $this->em->flush();
                    break;
                default:
                    /* Not expected. */
                    $this->addTransition($documentStatus, $now, $documentStatus::STATE_UNKNOWN, self::COUNT_IGNORE);
                    $this->em->flush();
                    break;
            }
        } catch (ORMException|DBALException $ex) {
            #TODO Check if this logger is reliable in this context.
            /* It is not critical. */
            $level = self::LOG_SEVERITY_LEVEL_WARNING;
            PrestaShopLogger::addLog(
                $ex->getMessage(),
                $level,
                $ex->getCode()
            );

            $rapportError = true;
        }

        if ($rapportError) {
            throw new DatabaseError('Cannot finish queue.');
        }
    }

    public function forceDownloadRecipe($orderId)
    {
        $level = self::LOG_SEVERITY_LEVEL_NOTICE;
        PrestaShopLogger::addLog(
            "Forced download of e-paragon of order $orderId.",
            $level
        );

        $order = new Order($orderId);
        if ($order->id) {
            $documentStatus = $this->getDocumentStatusIfExists($order->id);
            if ($documentStatus) {
                $state = $documentStatus->getDocumentState();
                if ($documentStatus::STATE_QUEUED !== $state) {
                    $now = new DateTime();
                    $randomId = $now->format('ym') . '-' . bin2hex(random_bytes(8)) . '_forced';
                    $documentStatus->setTextId($randomId);
                    $this->addTransition($documentStatus, $now, $documentStatus::STATE_QUEUED, self::COUNT_RESET);
                    $this->em->flush();
                }
            } else {
                $documentStatus = $this->createEmpty($order->id);
            }

            return $this->downloadRecipe($documentStatus);
        } else {
            return false;
        }
    }

    /**
     * Try download recipe.
     *
     * This method does not throw.
     *
     * @param EparagonyDocumentStatus $documentStatus
     * @return bool True for success.
     */
    public function downloadRecipe(EparagonyDocumentStatus $documentStatus)
    {
        return $this->downloadRecipeInternal($documentStatus, false);
    }

    /**
     * Try download recipe (internal use).
     *
     * We have to skip check if it has been executed before.
     *
     * This method does not throw.
     *
     * @param EparagonyDocumentStatus $documentStatus
     * @param bool $skipCheck
     * @return bool True for success.
     */
    private function downloadRecipeInternal(EparagonyDocumentStatus $documentStatus, $skipCheck)
    {
        try {
            if ($skipCheck) {
                $canDownload = true;
            } else {
                $canDownload = $this->prepForSending($documentStatus);
            }
            if (!$canDownload) {
                /* The document cannot be downloaded. */
                return false;
            }
            $order = new Order($documentStatus->getOrderId());

            $textId = $documentStatus->getTextId();
            $api = $this->getApiSpark();
            list($command, $url, $sparkToken) = $api->getRecipeCommand($order, $textId);
            $rest = json_decode($documentStatus->getRest(), true);
            if (!is_array($rest)) {
                $rest = [];
            }
            $rest['command'] = $command;
            $rest['url'] = $url;
            $rest['sparkToken'] = $sparkToken;
            $documentStatus->setRest(json_encode($rest));
            /* The function below flush. */
            $this->finishSending($documentStatus);

            return true;
        } catch (ApiSparkException $ex) {
            /* It is not critical. */
            $level = self::LOG_SEVERITY_LEVEL_WARNING;
            PrestaShopLogger::addLog(
                $ex->getMessage(),
                $level,
                $ex->getCode()
            );

            return false;
        } catch (ORMException|DBALException $ex) {
            #TODO Check if this logger is reliable in this context.
            /* It is not critical. */
            $level = self::LOG_SEVERITY_LEVEL_WARNING;
            PrestaShopLogger::addLog(
                $ex->getMessage(),
                $level,
                $ex->getCode()
            );

            return false;
        }
    }

    public function finishSending(EparagonyDocumentStatus $status)
    {
        $now = new DateTime();
        $state = $status->getDocumentState();
        if ($status::STATE_SENDING === $state) {
            $this->addTransition($status, $now, $status::STATE_SENT, self::COUNT_ZERO);
        } else {
            $this->addTransition($status, $now, $status::STATE_UNKNOWN, self::COUNT_IGNORE);
        }
        $this->em->flush();
    }

    private function prepForSending(EparagonyDocumentStatus $status)
    {
        $state = $status->getDocumentState();
        $now = new DateTime();
        if ($status::STATE_QUEUED === $state) {
            $this->addTransition($status, $now, $status::STATE_SENDING, self::COUNT_RESET);
            $this->em->flush();
            return true;
        } elseif (EparagonyDocumentStatus::STATE_SENDING === $state) {
            $timeLimit = new DateTime('10 minutes ago');
            if ($status->getUpdated() > $timeLimit) {
                /* It is expected this state will be changed from different thread in few seconds. */
                $inOneMinute = new DateTime('1 minute');
                $status->setChecked($inOneMinute);
                $this->em->flush();
                return false;
            } else {
                $count = $status->getRetryCount();
                if ($count >= self::MAX_RETRY) {
                    $this->addTransition($status, $now, $status::STATE_ERROR, self::COUNT_IGNORE);
                    $this->em->flush();
                    return false;
                } else {
                    $newCount = $this->addTransition($status, $now, $status::STATE_SENDING, self::COUNT_INCREMENT);
                    /* We want to wait more each time. The tenth time is over one day. */
                    $seconds = round(3.2 ** ($newCount - 1) + 600);
                    $checkAt = new DateTime("$seconds seconds");
                    $status->setChecked($checkAt);
                    $this->em->flush();
                    return true;
                }
            }
        } else {
            /* Nothing sane to do. */
            return false;
        }
    }

    /**
     * Get Spark API
     *
     * @return ApiSpark
     */
    private function getApiSpark()
    {
        if (!isset($this->apiSpark)) {
            $this->apiSpark = $this->apiSparkFactory->getApiClass();
        }

        return $this->apiSpark;
    }

    public function tryRepeatRegister()
    {
        $repo = $this->em->getRepository(EparagonyDocumentStatus::class);
        assert($repo instanceof EparagonyDocumentStatusRepository);

        /* 10 additional seconds. */
        $stop = time() + 10;
        do {
            $status =  $repo->getFromQueue();
            if (!$status) {
                /* Early exit. */
                return;
            }
            $ready = $this->prepForSending($status);
            if ($ready) {
                /* It is checked. */
                $this->downloadRecipeInternal($status, true);
            }
        } while (time() < $stop);
    }
}
