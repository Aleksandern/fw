<?php
namespace app\core\module\model;

use app\core\Db;

class Module 
{
    protected $db;
    protected $varpub;

    function __construct () 
    {
        $this->varpub = \VarPub::get();
        $this->db = new Db();
    }
    public function getData() 
    {
    } 
}
