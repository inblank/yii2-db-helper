<?php

namespace inblank\dbhelper;

/**
 * Override the standard Connect class to implement additional functions
 */
class Connection extends \yii\db\Connection
{
    /**
     * The scheme used by default.
     * If not set, then for 'mysql' set to database name, for `pgsql` set to 'public'
     * @var string
     */
    public $defaultScheme;
    /**
     * Cache full table names
     * @var array
     */
    private $tablesCache = [];

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
        if (empty($this->defaultScheme)) {
            $this->defaultScheme = $this->getDriverName() === 'pgsql' ? 'public' : $this->getDsnAttribute('dbname');
        }
        return $this->tablesCache[$tableName] = '{{' . $this->defaultScheme . '}}.' . $tableName;
    }

    /**
     * Getting attribute value from DSN string
     * @param string $attributeName attribute name
     * @return string|null if attribute is not found, returns null
     */
    public function getDsnAttribute($attributeName)
    {
        return preg_match('/' . $attributeName . '=([^;]*)/', $this->dsn, $match) ? $match[1] : null;
    }
}
