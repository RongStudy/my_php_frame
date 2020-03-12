<?php

namespace Frame;

/**
 * 控制器
 * Class Controller
 * @package Frame
 */
abstract class Controller
{
    protected $data;
    protected $controller_name;
    protected $action_name;
    protected $template_dir;

    public function __construct($controller_name, $action_name)
    {
        $this->controller_name = $controller_name;
        $this->action_name = $action_name;
        $this->template_dir = Application::getInstance()->base_dir . '/App/View';
    }

    public function assign($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function display($file = '')
    {
        if (empty($file)) {
            $file = strtolower($this->controller_name) . '/' . $this->action_name . '.php';
        } else {
            $file = str_replace('\\', '/', $file);
            $file = trim($file);
            $file = trim($file, '/');
            $hasx = strpos($file, '/');
            if ($hasx !== false) {
                $file_arr = explode('/', $file);
                if (!$file_arr[0] == $this->controller_name) {
                    $file = $this->controller_name . '/' . $file;
                }
            } else {
                $file = $this->controller_name . '/' . $file;
            }

            if (substr($file, -4, strlen($file)) != '.php') {
                $file .= '.php';
            }
        }
        $path = $this->template_dir . '/' . $file;
        if (!file_exists($path)) {
            exit($path . ' is not exists');
        }
        if (!empty($this->data)) {
            extract($this->data);
        }
        require_once $path;
    }
}