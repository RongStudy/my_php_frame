<?php


namespace Frame;


class Application
{
    public $base_dir;
    private $displtch = true;

    private static $config;
    private static $instance;

    private function __construct($base_dir)
    {
        $this->base_dir = $base_dir;
        self::$config = new Config($base_dir . '/configs');
        $this->classAlias();
        $this->loader();
        $this->loger();
    }

    private function __clone(){}

    private function classAlias()
    {
        class_alias('Frame\\Application', 'Frame\\App');
    }

    private function loader()
    {
        is_file($this->base_dir . '/App/common.php') ? include_once $this->base_dir . '/App/common.php' : '';
    }

    private function loger()
    {
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();
    }

    /**
     * 单例模式
     * @param string $base_dir
     * @return Application
     */
    public static function getInstance($base_dir = '')
    {
        if (empty(self::$instance)) {
            self::$instance = new self($base_dir);
        }
        return self::$instance;
    }

    public function dispatch()
    {
        if (!$this->displtch) {
            exit('can\'t');
        }
        $this->displtch = false;
        $uri = trim($_SERVER['REQUEST_URI'], '/');
        $uri_arr = explode('/', $uri);
        if ($uri == '' || $uri[0] == '?' || $uri == '/' || count($uri_arr) == 1) {
            $controller = ucwords(trim(self::$config['config']['default_controller']));
            $default_action = self::$config['config']['default_action'];
            $action = strtolower($default_action[0]) . substr($default_action, 1, strlen($default_action));
        } else {
            $controller = $uri_arr[0];
            if (strpos($uri_arr[1], '?') !== false) {
                $action = substr($uri_arr[1], 0, strpos($uri_arr[1], '?'));
            } else {
                $action = $uri_arr[1];
            }
        }

        $controller_low = strtolower($controller);
        $controller = ucwords($controller);
        $class = '\\App\\Controller\\' . $controller;
        if (!method_exists($class, $action)) {
            exit($class . '::' . $action . ' is not exists');
        }
        $obj = new $class($controller, $action);
        $controller_config = self::$config['controller'];
        $decorators = array(); # 装饰器
        if (isset($controller_config[$controller_low]['decorator'])) {
            $conf_decorator = $controller_config[$controller_low]['decorator'];
            foreach ($conf_decorator as $class) {
                $decorators[] = new $class;
            }
        }
        foreach ($decorators as $decorator) {
            $decorator->beforeRequest($obj);
        }
        $return_value = $obj->$action();
        if ($return_value) {
            foreach ($decorators as $decorator) {
                $decorator->afterRequest($return_value);
            }
        }
    }

    /**
     * 获取配置
     * @param string $file_name 配置文件名
     * @return array|mixed
     */
    public function getConfig($file_name = '')
    {
        if ($file_name) {
            return self::$config[$file_name];
        }
        return array();
    }
}