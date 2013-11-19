<?php
namespace app\core;

class Token
{
    private $config;
    private $varpub;

    function __construct()
    {
        $this->config = \Config::get();        
        $this->varpub = \VarPub::get();

    }
    
    // генерируем token и запоминаем
    public function gen()
    {
        $data = session_id().Ip::get().UserAgent::get();
        $salt = site_path.hash('md5', $this->config['db']['pass']);
        $token = hash ('sha256', $data.$salt);
        $this->varpub->token = $token;
        //echo '<hr>'.$token.'<hr>';
    }

    // проверяем token, если он есть в POST или в GET
    public function check()
    {
        if ($this->config['token']) {
            if (!empty($_POST) || GetInp::has('token')) {
                $token = GetInp::gp('token');
                if ($token != $this->varpub->token) die ('Token is wrong!');
            }
        }
    }
}
