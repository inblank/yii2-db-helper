<?php

namespace inblank\dbhelper;

/**
 * Override migration class
 */
class Migration extends \yii\db\Migration
{

    /**
     * Additional options when creating tables
     * @var string
     */
    public $params;

    /**
     * Sign of working with DBMS MySQL
     * @var bool
     */
    protected $isMysql = false;

    /**
     * {@inheritDoc}
     */
    public function init()
    {
        parent::init();
        switch ($this->getDb()->driverName) {
            case 'mysql':
                $this->params = 'ENGINE InnoDB CHARSET utf8 COLLATE utf8_unicode_ci';
                $this->isMysql = true;
                break;
        }
    }

    /**
     * Creating a table
     * @param string $table table name
     * @param array $columns table fields definition
     * @param string $comment table comment
     * @param string $options advanced table creation options
     * @throws \yii\db\Exception
     */
    public function createTable($table, $columns, $comment = null, $options = null)
    {
        if (strpos($table, '{{%') !== 0) {
            // giving the table name to the prefix notation
            $table = $this->tn($table);
        }
        parent::createTable($table, $columns, $this->params . $options);
        $this->getDb()->createCommand()->addCommentOnTable($table, $comment)->execute();
    }

    /**
     * Getting the full name of the table with the prefix label
     * @param string $tab table name
     * @return string
     */
    public function tn($tab)
    {
        $db = $this->getDb();
        return $db instanceof Connection ? $db->tableName($tab) : '{{%' . $tab . '}}';
    }
    
    /**
     * Creates indexes by list
     * @param string $table table the name of the table for which we create indexes
     * @param array $list list field names and uniqueness for indexes. Each item define one index
     *  Field names in the list key, uniqueness in value. If given simply by a string,
     *  then the index is not unique. For a composite index, field names are separated by commas.
     *  Examples:
     *      ['field1', 'field2'=>1, 'field3,field', 'field4,field5'=>1]
     */
    public function createIndexByList($table, $list)
    {
        foreach ($list as $fields => $unique) {
            if (is_numeric($fields)) {
                // if set only fields, then not unique index
                $fields = $unique;
                $unique = 0;
            }
            $fields = explode(',', $fields);
            $fullTableName = strpos($table, '{{%') === 0 ? $table : $this->tn($table);
            $this->createIndex($table . '_' . implode('_', $fields), $fullTableName, $fields, $unique);
        }
    }
}
