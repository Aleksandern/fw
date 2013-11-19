<?php
namespace app\core;

class Cookie
{
    public static function set($var, $val)
    {
        $var = $var.self::getPlace($var);
        $config = \Config::get();
        $host = $config['host'];
        $expire = time()+$config['cookie_expire'];
        setcookie($var, $val, $expire, '/', '.'.$host, false, true);
    }

    public static function del ($var, $delall = false) 
    {
        if (!$delall) $var = $var.self::getPlace($var);
        $config = \Config::get();
        $host = $config['host'];
        $expire = time()-$config['cookie_expire'];        
        setcookie($var, '', $expire, '/', '.'.$host, false, true);        
    }

    public static function get ($var)
    {
        $var = $var.self::getPlace($var);
        $var_res = '';
        $var_det =  filter_has_var(INPUT_COOKIE, $var);        
        if ($var_det) $var_res = filter_input(INPUT_COOKIE, $var, FILTER_SANITIZE_SPECIAL_CHARS);
        if (!$var_res) $var_res = '';

        return $var_res;
    }

    public static function delAll ()
    {
        foreach ($_COOKIE as $var => $val) {
            self::del($var, true);
            //echo $var.'<br>';
        }
    }

    private static function getPlace($var)
    {
        if ($var!='lang') {
            $varpub = \VarPub::get();
            $place = '_'.$varpub->place;
        } else $place = NULL;

        return $place;
    }
}
