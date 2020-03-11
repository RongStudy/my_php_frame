<?php

namespace Frame;
interface Decorator
{
    public function beforeRequest($controller);

    public function afterRequest($return_value);
}