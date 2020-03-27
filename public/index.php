<?php

define('BASEDIR', dirname(__DIR__));
define('IS_TEST', false);

/*
 * require BASEDIR . '/Frame/Loader.php';
 * spl_autoload_register('\\Frame\\Loader::autoload');
 */
require_once BASEDIR . '/vendor/autoload.php';
\Frame\Application::getInstance(BASEDIR)->dispatch();