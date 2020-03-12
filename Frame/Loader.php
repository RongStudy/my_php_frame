<?php


namespace Frame;


class Loader
{
    private function __construct(){}

    public static function autoload($class)
    {
        $file_path = str_replace('\\', '/', BASEDIR . '/' . str_replace('\\', '/', $class) . '.php');
        if (!file_exists($file_path)) {
            exit($file_path . ' is not exists');
        }
        require_once BASEDIR . '/' . str_replace('\\', '/', $class) . '.php';
    }
}