<?php

namespace Frame\Interfaces;

interface Decorator
{
    public function beforeRequest($controller);

    public function afterRequest($value);
}