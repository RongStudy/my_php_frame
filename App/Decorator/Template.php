<?php

namespace App\Decorator;
use Frame\Interfaces\Decorator;

class Template implements Decorator
{
    protected $controller;

    function beforeRequest($controller)
    {
        $this->controller = $controller;
    }

    function afterRequest($value)
    {
        if (isset($_GET['app']) && $_GET['app'] == 'html') {
            foreach ($value as $k => $v) {
                $this->controller->assign($k, $v);
            }
            $this->controller->render();
        }
    }
}