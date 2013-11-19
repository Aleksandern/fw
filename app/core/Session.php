<?php
namespace app\core;

class Session
{
    public static function get ($var)
    {
        $varpub = \VarPub::get();
        $place = $varpub->place;
        if (isset($_SESSION[$place][$var])) return $_SESSION[$place][$var];
        return '';
    }

    public static function set ($var, $val)
    {
        $varpub = \VarPub::get();
        $place = $varpub->place;
        $_SESSION[$place][$var] = $val;
    }

    public static function del ($var)
    {
        $varpub = \VarPub::get();
        $place = $varpub->place;
        if (isset($_SESSION[$var])) unset ($_SESSION[$var]);
    }

    public static function delAll ()
    {
        foreach ($_SESSION as $var => $val) {
            self::del($var);
        }
    }
    
}
