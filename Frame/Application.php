<?php


namespace Frame;


class Application
{
    public $base_dir;
    protected static $instance;

    public $config;

    protected function __construct($base_dir)
    {
        $this->base_dir = $base_dir;
        $this->config = new Config($base_dir . '/configs');
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
        $uri = trim($_SERVER['REQUEST_URI'], '/');
        $uri_arr = explode('/', $uri);
        if ($uri == '' || $uri[0] == '?' || $uri == '/' || count($uri_arr) == 1) {
            $c = ucwords(trim($this->config['config']['default_controller']));
            $default_action = $this->config['config']['default_action'];
            $v = strtolower($default_action[0]) . substr($default_action, 1, strlen($default_action));
        } else {
            $c = $uri_arr[0];
            if (strpos($uri_arr[1], '?')!==false) {
                $v = substr($uri_arr[1], 0, strpos($uri_arr[1], '?'));
            } else {
                $v = $uri_arr[1];
            }
        }

        $c_low = strtolower($c);
        $c = ucwords($c);
        $class = '\\App\\Controller\\'.$c;
        if (!method_exists($class, $v)) {
            exit($class . ' -> function ' . $v . ' is not exists');
        }
        $obj = new $class($c, $v);
        $controller_config = $this->config['controller'];
        $decorators = array(); # 装饰器
        if (isset($controller_config[$c_low]['decorator'])) {
            $conf_decorator = $controller_config[$c_low]['decorator'];
            foreach ($conf_decorator as $class) {
                $decorators[] = new $class;
            }
        }
        foreach ($decorators as $decorator) {
            $decorator->beforeRequest($obj);
        }

        $return_value = $obj->$v();
        if ($return_value) {
            foreach ($decorators as $decorator) {
                $decorator->afterRequest($return_value);
            }
        }
    }


}