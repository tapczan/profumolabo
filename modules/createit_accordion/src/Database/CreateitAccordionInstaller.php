<?php

declare(strict_types=1);

namespace PrestaShop\Module\CreateitAccordion\Database;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\Statement;

class CreateitAccordionInstaller
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var string
     */
    private $dbPrefix;

    /**
     * @var string
     */
    private $mysqlEngine;

    /**
     * @param Connection $connection
     * @param string $dbPrefix
     * @param string $mysqlEngine
     */
    public function __construct(
        Connection $connection,
        string $dbPrefix,
        string $mysqlEngine
    ) {
        $this->connection = $connection;
        $this->dbPrefix = $dbPrefix;
        $this->mysqlEngine = $mysqlEngine;
    }

    /**
     * @return array
     *
     * @throws DBALException
     */
    public function createTables()
    {
        $errors = [];
        $this->dropTables();
        $sqlInstallFile = __DIR__ . '/../../Resources/data/install.sql';
        
        if (!file_exists($sqlInstallFile)) {
            $errors[] = 'Something went wrong.';
        } elseif (!$sqlQueries = file_get_contents($sqlInstallFile)) {
            $errors[] = 'Something went wrong.';
        }
        
        $sqlQueries = str_replace(['PREFIX_', 'ENGINE_TYPE'], [$this->dbPrefix, $this->mysqlEngine], $sqlQueries);
        $sqlQueries = preg_split("/;\s*[\r\n]+/", trim($sqlQueries));

        foreach ($sqlQueries as $query) {
            if (empty($query)) {
                continue;
            }
            $statement = $this->connection->executeQuery($query);
            if (0 != (int) $statement->errorCode()) {
                $errors[] = [
                    'key' => json_encode($statement->errorInfo()),
                    'parameters' => [],
                    'domain' => 'Admin.Modules.Notification',
                ];
            }
        }

        return $errors;
    }

    /**
     * @return array
     *
     * @throws DBALException
     */
    public function dropTables()
    {
        $errors = [];
        $tableNames = [
            'createit_accordion',
            'createit_accordion_header',
            'createit_accordion_content'
        ];
        foreach ($tableNames as $tableName) {
            $sql = 'DROP TABLE IF EXISTS ' . $this->dbPrefix . $tableName;
            $statement = $this->connection->executeQuery($sql);
            if ($statement instanceof Statement && 0 != (int) $statement->errorCode()) {
                $errors[] = [
                    'key' => json_encode($statement->errorInfo()),
                    'parameters' => [],
                    'domain' => 'Admin.Modules.Notification',
                ];
            }
        }

        return $errors;
    }
}