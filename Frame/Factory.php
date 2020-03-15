<?php


namespace Frame;

/**
 * 工厂模式
 * Class Factory
 * @package Frame
 */
class Factory
{
    /**
     * @param $modelName
     * @return bool|mixed
     * @throws \Exception
     */
    public static function getModel($modelName)
    {
        $key = 'app_model_' . $modelName;
        if (!Register::get($key)) {
            $class = '\\App\\Model\\' . ucwords($modelName);
            if (!class_exists($class)) {
                throw_except($class . ' is not exists');
            }
            Register::set($key, new $class);
        }
        return Register::get($key);
    }

    public static function getDB($db_type = 'slave', $driver = 'mysqli', $slave_name = 'slave1')
    {
        $db_conf = Application::getInstance()->getConfig('database');
        $key = 'db_';
        if ($db_type == 'slave') {
            $db_conf = $db_conf[$db_type][$slave_name] ?? array();
            $key .= ($db_type.'_'.$slave_name);
        } else {
            $db_conf = $db_conf[$db_type] ?? array();
            $key .= $db_type;
        }

        $db = Register::get($key);
        if (!$db) {
            $driver_name = 'Frame\\Database\\Db' . ucwords(strtolower(trim($driver)));
            $db = new $driver_name($db_conf, $key);
            Register::set($key, $db);
        }
        return $db;
    }
}