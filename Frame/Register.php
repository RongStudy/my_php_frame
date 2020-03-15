<?php


namespace Frame;

/**
 * 注册器模式
 * Class Register
 * @package Frame
 */
abstract class Register
{
    protected static $object = array();

    public static function set($alias, $object)
    {
        self::$object[$alias] = $object;
    }

    public static function get($key)
    {
        if (!isset(self::$object[$key])) {
            return false;
        }
        return self::$object[$key];
    }

    public static function _unset($alias)
    {
        unset(self::$object[$alias]);
    }
}