<?php
/**
 * @author Check AUTHORS file.
 * @copyright Spark
 * @license proprietary
 */

namespace Spark\EParagony;

use Spark\EParagony\Entity\EparagonyPrinterLog;
use DateTime;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use PrestaShopLogger;

class PrinterLogManager
{
    private $em;

    public function __construct(
        EntityManagerInterface $em
    ) {
        $this->em = $em;
    }

    public function addLog(array $payload)
    {
        try {
            $now = new DateTime;
            $log = new EparagonyPrinterLog();
            $documentTextId = $payload['RID'] ?? '(null)';
            $payloadString = json_encode($payload, JSON_PRETTY_PRINT);
            $log
                ->setCreated($now)
                ->setDocumentTextId($documentTextId)
                ->setPayload($payloadString)
            ;
            $this->em->persist($log);
            $this->em->flush();
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

    /**
     * The function will return a simple representation.
     *
     * @param string $documentId Document id in a text form, RID in print server API.
     */
    public function getLogForDocumentId($documentId)
    {
        $repo = $this->em->getRepository(EparagonyPrinterLog::class);
        $data = $repo->findBy(['documentTextId' => $documentId]);

        $mapper = function (EparagonyPrinterLog $log) {
            return $log->getSimpleRepresentation();
        };
        $data = array_map($mapper, $data);

        $sorter = function (PrinterLogSimple $a, PrinterLogSimple $b) {
            return $b <=> $a;
        };
        usort($data, $sorter);

        return $data;
    }
}
