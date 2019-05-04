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
                $this->params = 'ENGINE innodb CHARSET utf8 COLLATE utf8_unicode_ci';
                $this->isMysql = true;
                break;
        }
    }

    /**
     * Getting the full name of the table with the prefix label
     * @param string $tab table name
     * @return string
     */
    public function tn($tab)
    {
        return '{{%' . $tab . '}}';
    }

    /**
     * Creating a table
     * @param string $table table name
     * @param array $columns table fields definition
     * @param string $comment table comment
     * @param string $options advanced table creation options
     */
    public function createTable($table, $columns, $comment = null, $options = null)
    {
        if (strpos($table, '{{%') != 0) {
            // giving the table name to the prefix notation
            $table = $this->tn($table);
        }
        parent::createTable($table, $columns, $this->params
            . (empty($comment) ? '' : " COMMENT '{$comment}' ") . $options);
    }
}