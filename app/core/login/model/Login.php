<?php
namespace app\core\login\model;

use \app\core\module\model\Module;

class Login extends Module
{
    private static $users_table;

    public function setTableName()
    {
        $place = $this->varpub->place;        
        if ($place == 'adm') $place = 'adm_';
        else $place = NULL;

        self::$users_table = $place.'users';

    }
    public function userInfoLogin ($login)
    {
        $res = $this->db->select("SELECT * FROM `".self::$users_table."` WHERE login=? ", $login, 1);
        return $res;
    }

    public function userInfoId ($id)
    {
        $res = $this->db->select("SELECT * FROM `".self::$users_table."` WHERE id=? ", $id, 1);
        return $res;
    }

    public function checkId ($id)
    {
        $res = $this->db->select("SELECT id FROM `".self::$users_table."` WHERE id=? ", $id, 1);
        if (!empty($res)) return true;
        return false;
    }

    public function updPasswCookie($id, $data)
    {
        $data = $data.','.$id;
        $this->db->query("UPDATE `".self::$users_table."` SET passw_cookie=? WHERE id=? ", $data);
    }
    
}
