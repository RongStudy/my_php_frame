<?php

namespace App\Controller;

use Exception;
use Frame\Controller;

class Index extends Controller
{
    /**
     * 使用 twig 模板
     * @throws Exception
     */
    public function index()
    {
        $html = '<h1 style="text-align: center;">Hello My_Frame😉</h1>';
        $this->assign('data', $html);
        $this->render();
    }

    /**
     * 不使用 twig 模板
     * @throws Exception
     */
    public function index2()
    {
        $html = '<h1 style="text-align: center;">Hello My_Frame</h1>';
        $this->assign('data', $html);
        $this->display();
    }
}