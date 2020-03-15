<?php

namespace App\Controller;

use Frame\App;
use Frame\Controller;

class Index extends Controller
{
    /**
     * 使用 twig 模板
     */
    public function index()
    {
        $html = '<h1 style="text-align: center;">Hello My_Frame</h1>';
        $this->assign('data', $html);
        $this->assign('data2', ['num1' => 1, 'num2' => ['num22' =>22]]);
        $this->render();
    }

    /**
     * 不使用 twig 模板
     */
    public function index2()
    {
        $html = '<h1 style="text-align: center;">Hello My_Frame</h1>';
        $this->assign('data', $html);
        $this->display('ss');
    }
}