<?php
namespace app\core;

class GetInp
{
    public static function gp ($gp, $method='')
    {
        $gp_det = self::detMethod($gp, $method);        

        $gp_res = filter_input($gp_det, $gp, FILTER_SANITIZE_SPECIAL_CHARS); 
        if (!$gp_res) $gp_res = '';

        return trim($gp_res);
    }

    public static function gp_arr($gp)
    {
        $gp_det = self::detMethod($gp);
        $gp_res =  filter_input_array($gp_det, Array ($gp => Array( 'filter' => FILTER_SANITIZE_SPECIAL_CHARS, 'flags'  => FILTER_REQUIRE_ARRAY)));
        if (!$gp_res||empty($gp_res[$gp])) $gp_res = Array();

        return $gp_res;
    }

    public static function has($gp, $method='')
    {
        $gp_det = self::detMethod($gp, $method);        
        return filter_has_var($gp_det, $gp);
    }

    private static function detMethod ($gp, $method = '')
    {
        switch ($method) {
            case 'get':
                $gp_det = INPUT_GET;
                break;
            case 'post':
                $gp_det = INPUT_POST;
                break;
            default:
                $gp_det =  filter_has_var(INPUT_POST, $gp);        
                if ($gp_det) $gp_det = INPUT_POST;
                $gp_det =  filter_has_var(INPUT_GET, $gp);        
                if ($gp_det) $gp_det = INPUT_GET;        
                break;
        }
        
        return $gp_det;
    }
}
