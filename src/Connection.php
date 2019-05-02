<?php

namespace inblank\dbhelper;

/**
 * Override the standard Connect class to implement additional functions
 */
class Connection extends \yii\db\Connection
{
    /**
     * Cache full table names
     * @var array
     */
    private $tablesCache = [];

    /**
     * Getting attribute value from DSN string
     * @param string $attributeName attribute name
     * @return string|null if attribute is not found, returns null
     */
    public function getDsnAttribute($attributeName)
    {
        return preg_match('/' . $attributeName . '=([^;]*)/', $this->dsn, $match) ? $match[1] : null;
    }

    /**
     * Getting the full name of the table with the database name
     * @param string $tableName the name of the table for which to get the full name
     * @return string
     */
    public function tableName($tableName)
    {
        if (isset($this->tablesCache[$tableName])) {
            return $this->tablesCache[$tableName];
        }
        $dbName = $this->getDsnAttribute('dbname');
        return $this->tablesCache[$tableName] = ($dbName ? '{{' . $dbName . '}}.' : '') . $tableName;
    }
}
