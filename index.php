<?php

define('BASEDIR', __DIR__);
require BASEDIR . '/Frame/Loader.php';
spl_autoload_register('\\Frame\\Loader::autoload');

echo \frame\Application::getInstance(BASEDIR)->dispatch();