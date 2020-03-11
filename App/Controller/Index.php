<?php

namespace App\Controller;

class Index extends \Frame\Controller
{
    public function index()
    {
        $array = ['yes' => 'one'];
        $this->assign('data', $array);
        $this->display();
    }

    public function index2()
    {
        echo 1231;
    }

    public function tester()
    {
        $this->display();
    }
}