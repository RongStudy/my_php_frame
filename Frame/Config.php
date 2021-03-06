<?php


namespace Frame;


class Config implements \ArrayAccess
{
    protected $path;
    protected $configs = array();

    function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($key)
    {
        return isset($this->configs[$key]);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($key)
    {
        if (empty($this->configs[$key])) {
            $file_path = $this->path . '/' . $key . '.php';
            if (!file_exists($file_path)) {
                return array();
            }
            $config = require_once $file_path;
            $this->configs[$key] = $config;
        }
        return $this->configs[$key];
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($key, $value)
    {
        exit("Can\'t write config file.");
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($key)
    {
        unset($this->configs[$key]);
    }
}