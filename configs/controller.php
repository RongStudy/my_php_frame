<?php

return array(
    'home' => array(
        'decorator' => array(
            //'App\Decorator\Login',
            //'App\Decorator\Template',
            //'App\Decorator\Json',
        ),
    ),
    'index' => array(
        'decorator' => array(
            'App\Decorator\Template',
        ),
    ),
    'default' => 'hello world',
);