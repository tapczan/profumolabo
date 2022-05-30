<?php
/**
 * @author Check AUTHORS file.
 * @copyright Spark
 * @license proprietary
 */

namespace Spark\EParagony;

use Spark\EParagony\Entity\EparagonyDocumentStatus;
use Spark\EParagony\SparkApi\ApiSparkException;
use Spark\EParagony\SparkApi\ApiSparkFactory;
use DateTime;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Exception;
use OrderHistory;
use PrestaShop\PrestaShop\Adapter\Entity\Order;
use PrestaShop\PrestaShop\Adapter\Entity\OrderState;
use PrestaShopLogger;

class DocumentsManager
{
    private $em;
    private $receiptManager;
    private $mailer;
    private $apiSparkFactory;
    private $orderChecker;

    public function __construct(
        EntityManagerInterface $em,
        ReceiptQueueManager $receiptManager,
        Mailer $mailer,
        ApiSparkFactory $apiSparkFactory,
        OrderChecker $orderChecker
    ) {
        $this->em = $em;
        $this->receiptManager = $receiptManager;
        $this->mailer = $mailer;
        $this->apiSparkFactory = $apiSparkFactory;
        $this->orderChecker = $orderChecker;
    }

    public function getDocumentStatusIfExists($orderId) : ?EparagonyDocumentStatus
    {
        $repo = $this->em->getRepository(EparagonyDocumentStatus::class);

        return $repo->findOneBy(['orderId' => $orderId]);
    }

    public function handleHistoryChange(OrderHistory $orderHistory)
    {
        $orderState = new OrderState($orderHistory->id_order_state);
        if (!$this->orderChecker->isDocumentPossibleForState($orderState)) {
            /* Nothing to do. */
            return;
        }

        $order = new Order($orderHistory->id_order);
        if (!$this->orderChecker->isApplicableForOrder($order)) {
            /* Nothing to do. */
            return;
        }

        $documentStatus = $this->getDocumentStatusIfExists($order->id);
        if (!$documentStatus) {
            try {
                $documentStatus = $this->receiptManager->createEmpty($order->id);
                if (!$documentStatus) {
                    /* There is an error log. Ignore creation of receipt. */
                    return;
                }
            } catch (ORMException|DBALException $ex) {
                #TODO Check if this logger is reliable in this context.
                /* It is not critical. */
                $level = PrestaShopLogger::LOG_SEVERITY_LEVEL_WARNING;
                PrestaShopLogger::addLog(
                    $ex->getMessage(),
                    $level,
                    $ex->getCode()
                );
                return;
            }
        }

        /* We can assume the method below is not thrown. */
        $this->downloadRecipe($documentStatus);
    }

    public function getFromQueue()
    {
        try {
            $status = $this->getFromQueueInternal();
            if ($status) {
                $ok = $this->receiptManager->prepForUpload($status);
                if ($ok) {
                    return $status;
                } else {
                    return null;
                }
            }
        } catch (ORMException|DBALException $ex) {
            #TODO Check if this logger is reliable in this context.
            /* It is not critical. */
            $level = PrestaShopLogger::LOG_SEVERITY_LEVEL_WARNING;
            PrestaShopLogger::addLog(
                $ex->getMessage(),
                $level,
                $ex->getCode()
            );

            return null;
        }
    }

    private function getFromQueueInternal()
    {
        $documentStatus = $this->receiptManager->getFromQueue();
        if ($documentStatus) {
            if ($this->receiptManager->needDownloadStep($documentStatus)) {
                $canDownload = $this->receiptManager->prepForDownload($documentStatus);
                if ($canDownload) {
                    $downloaded = $this->downloadRecipe($documentStatus);
                    if ($downloaded) {
                        return $documentStatus;
                    }
                }
            } else {
                return $documentStatus;
            }
        }

        return null;
    }

    public function getByTag($tag) : ?EparagonyDocumentStatus
    {
        $repo = $this->em->getRepository(EparagonyDocumentStatus::class);
        $one = $repo->findOneBy(['textId' => $tag]);
        assert(!$one | $one instanceof EparagonyDocumentStatus);

        return $one;
    }

    /**
     * The method to mark document queue as finished.
     *
     * This function do not distinct between finishing with and without error.
     * Only database errors are reported back, as exception.
     *
     * @param EparagonyDocumentStatus $documentStatus
     * @throws DatabaseError
     */
    public function finishQueue(EparagonyDocumentStatus $documentStatus)
    {
        try {
            $this->receiptManager->finishQueue($documentStatus);
            $rapportError = false;
        } catch (ORMException|DBALException $ex) {
            #TODO Check if this logger is reliable in this context.
            /* It is not critical. */
            $level = PrestaShopLogger::LOG_SEVERITY_LEVEL_WARNING;
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

    public function confirmQueue(EparagonyDocumentStatus $documentStatus)
    {
        try {
            $this->receiptManager->confirmQueue($documentStatus);
            $rapportError = false;

            /* Send after flush. */
            $this->mailer->mailReceipt($documentStatus);
        } catch (ORMException|DBALException $ex) {
            #TODO Check if this logger is reliable in this context.
            /* It is not critical. */
            $level = PrestaShopLogger::LOG_SEVERITY_LEVEL_WARNING;
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

    public function repeatQueue(EparagonyDocumentStatus $documentStatus)
    {
        try {
            $this->receiptManager->repeatQueue($documentStatus);
            $rapportError = false;
        } catch (ORMException|DBALException $ex) {
            #TODO Check if this logger is reliable in this context.
            /* It is not critical. */
            $level = PrestaShopLogger::LOG_SEVERITY_LEVEL_WARNING;
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

    public function cancelQueue(EparagonyDocumentStatus $documentStatus)
    {
        try {
            $this->receiptManager->cancelQueue($documentStatus);
            $rapportError = false;
        } catch (ORMException|DBALException $ex) {
            #TODO Check if this logger is reliable in this context.
            /* It is not critical. */
            $level = PrestaShopLogger::LOG_SEVERITY_LEVEL_WARNING;
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
        $order = new Order($orderId);
        if ($order->id) {
            $documentStatus = $this->getDocumentStatusIfExists($order->id);
            if ($documentStatus) {
                $this->receiptManager->forceQueued($documentStatus);
            } else {
                $documentStatus = $this->receiptManager->createEmpty($order->id);
            }

            $this->downloadRecipe($documentStatus);
            return true;
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
        try {
            $canDownload = $this->receiptManager->prepForDownload($documentStatus);
            if (!$canDownload) {
                /* The document cannot be downloaded. */
                return false;
            }
            $order = new Order($documentStatus->getOrderId());

            $textId = $documentStatus->getTextId();
            $api = $this->apiSparkFactory->getApiClass();
            list($command, $url, $sparkToken, $printer) = $api->getRecipeCommand($order, $textId);
            $rest = json_decode($documentStatus->getRest(), true);
            if (!is_array($rest)) {
                $rest = [];
            }
            $rest['command'] = $command;
            $rest['url'] = $url;
            $rest['sparkToken'] = $sparkToken;
            $rest['printer'] = $printer;
            $documentStatus->setRest(json_encode($rest));
            /* The function below flush. */
            $this->receiptManager->finishDownload($documentStatus);

            return true;
        } catch (ApiSparkException $ex) {
            /* It is not critical. */
            $level = PrestaShopLogger::LOG_SEVERITY_LEVEL_WARNING;
            PrestaShopLogger::addLog(
                $ex->getMessage(),
                $level,
                $ex->getCode()
            );

            return false;
        } catch (ORMException|DBALException $ex) {
            #TODO Check if this logger is reliable in this context.
            /* It is not critical. */
            $level = PrestaShopLogger::LOG_SEVERITY_LEVEL_WARNING;
            PrestaShopLogger::addLog(
                $ex->getMessage(),
                $level,
                $ex->getCode()
            );

            return false;
        }
    }
}
