<?php

namespace App\Payment;

class Configuration
{
    protected static $config = null;

    public static function get(string $key)
    {
        if (self::$config === null) {
            self::$config = require __DIR__ . "/config.php";
        }
        
        return self::$config[$key] ?? null;
    }
}
