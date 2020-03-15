<?php


namespace Frame;


abstract class Model
{
    protected static $table_name = '';
    protected static $observers = array();
    protected $where = [];

    public static function init($table_name)
    {
        $class_name_full = get_called_class();
        $class_name = explode('\\', $class_name_full);
        $class_name = strtolower(end($class_name));
        $key = 'app_model_' . $class_name;
        self::$table_name = $table_name;
        if (!Register::get($key)) {
            if (!class_exists($class_name_full, false)) {
                throw_except($class_name_full . ' is not found');
            }
            $conf_model = Application::getInstance()->getConfig('model');
            $observer = $conf_model[$class_name]['observer'] ?? [];
            if ($observer) {
                foreach ($observer as $class) {
                    self::$observers[] = new $class;
                }
            }
            $init = new static();
            Register::set($key, $init);
            return $init;
        }
        return Register::get($key);
    }

    public function notify()
    {
        foreach (self::$observers as $observer) {
            $observer->update();
        }
    }
}