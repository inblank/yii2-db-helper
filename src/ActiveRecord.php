<?php

namespace inblank\dbhelper;

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/**
 * ActiveRecord class to implement the necessary features
 */
class ActiveRecord extends \yii\db\ActiveRecord
{
    /**
     * {@inheritDoc}
     */
    public static function tableName()
    {
        $db = static::getDb();
        $tab = '{{%' . Inflector::camel2id(StringHelper::basename(get_called_class()), '_') . '}}';
        return $db instanceof Connection ? $db->tableName($tab) : $tab;
    }

}
