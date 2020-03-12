<?php

define('BASEDIR', dirname(__DIR__));
require BASEDIR . '/Frame/Loader.php';
spl_autoload_register('\\Frame\\Loader::autoload');

\frame\Application::getInstance(BASEDIR)->dispatch();