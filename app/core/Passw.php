<?php
namespace app\core;

class Passw
{
    private static $salt = '{heyEuflftim$634.,>l;}0';

    public static function enc($passw)
    {
        $passw = hash ('sha256', self::$salt.$passw);
        return $passw;
    }
}
