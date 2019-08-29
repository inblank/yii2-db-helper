<?php

namespace inblank\dbhelper;

use Yii;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/**
 * ActiveRecord class to implement the necessary features
 */
class ActiveRecord extends \yii\db\ActiveRecord
{
    /**
     * Getting a connection to the database considering the module settings
     * @return \yii\db\Connection the database connection used by this AR class.
     * @throws \yii\base\InvalidConfigException
     */
    public static function getDb()
    {
        static $db;
        if ($db === null) {
            $class = StringHelper::dirname(StringHelper::dirname(static::class)) . '\Module';
            foreach (Yii::$app->getModules() as $module) {
                if ($module instanceof $class) {
                    if (is_array($module->db)) {
                        if (!array_key_exists('class', $module->db)) {
                            $module->db['class'] = Connection::class;
                        }
                        $db = Yii::createObject($module->db);
                    } else {
                        $db = is_string($module->db) ? Yii::$app->get($module->db) : $module->db;
                    }
                    break;
                }
            }
            if ($db === null) {
                // did not find the module, using the default connection
                $db = Yii::$app->get('db');
            }
        }
        return $db;
    }

    /**
     * {@inheritDoc}
     * @throws \yii\base\InvalidConfigException
     */
    public static function tableName()
    {
        $db = static::getDb();
        $tab = '{{%' . Inflector::camel2id(StringHelper::basename(get_called_class()), '_') . '}}';
        return $db instanceof Connection ? $db->tableName($tab) : $tab;
    }

}
