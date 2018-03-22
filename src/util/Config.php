<?php
namespace Dingtalk\util;

class Config
{

    protected static $config = [];

    public static function get($name)
    {
        return isset(static::$config[$name]) ? static::$config[$name] : null;
    }

    public static function set($config)
    {
        static::$config = $config;
    }
}