<?php

namespace Frame;

/**
 * 控制器
 * Class Controller
 * @package Frame
 */
abstract class Controller
{
    protected $controller_name;
    protected $action_name;
    protected $template_dir;
    protected $assign_data = array();

    public function __construct($controller_name, $action_name)
    {
        $this->controller_name = $controller_name;
        $this->action_name = $action_name;
        $this->template_dir = Application::getInstance()->base_dir . '/App/View';
    }

    public function assign($key = '', $value = null)
    {
        if (is_array($value)) {
            foreach ($value as $keys => $val) {
                $this->assign_data[$key][$keys] = $val;
            }
        } else {
            $this->assign_data[$key] = $value;
        }
    }

    /**
     * 无模板引擎
     * @param string $file
     * @throws \Exception
     */
    public function display($file = '')
    {
        $suffix = App::getInstance()->getConfig('config');
        $suffix = $suffix['view_suffix'] ?? 'php';
        if (empty($file)) {
            $file = strtolower($this->controller_name) . '/' . strtolower($this->action_name) . ".{$suffix}";
        } else {
            $file = str_replace('\\', '/', $file);
            $file = trim($file);
            $file = trim($file, '/');
            $hasx = strpos($file, '/');
            if ($hasx !== false) {
                $file_arr = explode('/', $file);
                $file = strtolower($file_arr[0]) . '/' . strtolower($file_arr[1]);
            } else {
                $file = strtolower($this->controller_name) . '/' . $file;
            }
            if (substr($file, -4, strlen($file)) != ".{$suffix}") {
                $file .= ".{$suffix}";
            }
        }
        $path = $this->template_dir . '/' . $file;
        if (!file_exists($path)) {
            throw_except($path . ' is not exists!');
        }
        if (!empty($this->assign_data)) {
            extract($this->assign_data);
        }
        require_once $path;
    }

    /**
     * twig 模板引擎
     * @param string $path
     * @throws \Exception
     */
    public function render($path = '')
    {
        $suffix = App::getInstance()->getConfig('config');
        $suffix = $suffix['view_twig_suffix'] ?? 'twig';
        if (empty($path)) {
            $path = strtolower($this->controller_name) . '/' . strtolower($this->action_name);
        } else {
            $path = str_replace('\\', '/', $path);
            $path = trim($path);
            $path = trim($path, '/');
            $hasx = strpos($path, '/');
            if ($hasx !== false) {
                $path_arr = explode('/', $path);
                $path = strtolower($path_arr[0]) . '/' . $path_arr[1];
            } else {
                $path = strtolower($this->controller_name) . '/' . $path;
            }
        }
        $path .= ".{$suffix}";
        if (!file_exists($this->template_dir . '/' . $path)) {
            throw_except(($this->template_dir . '/' . $path) . ' is not exists!');
        }

        $key = 'app_twig';
        if (!Register::get($key)) {
            $loader = new \Twig\Loader\FilesystemLoader($this->template_dir);
            $twig = new \Twig\Environment($loader, [
                'cache' => BASEDIR . '/runtime',
                'auto_reload' => true
            ]);
            Register::set($key, $twig);
        }
        echo Register::get($key)->render($path, $this->assign_data);
    }
}