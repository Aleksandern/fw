<?php
namespace app\core\login\contr;

use \app\core\module\contr\Module;
use \app\core\GetInp;
use \app\core\Session;
use \app\core\Cookie;
use \app\core\Passw;
use \app\core\Redir;
use \app\core\UserAgent;
use \app\core\Ip;

class Login extends Module 
{
    private static $login_id_sess;
    private static $login_id_cook;

    public function actionIndex ($main_tmpl = true) 
    {
        // меняем главный шаблон на шаблон этого модуля
        //$this->varpub->tmpl = $this->tmpl_module_file;
        //$this->varpub->tmpl = site_path.DIRSEP.'ex.php';
        if ($main_tmpl) {
            $this->varpub->tmpl = $this->module_dir.DIRSEP.'view'.DIRSEP.'LoginOnly.php';
            if (GetInp::has('form')) $this->formAuth();
        //print_r ($_POST);
        }
        $this->model->setTableName();
        
        //echo $this->tmpl->lang_m_hz;



        //Cookie::delAll();

        //$this->checkSess();
        //print_r ($this->model->checkId(3));
    }

    public function formAuth()
    {
        $login = GetInp::gp('login');
        $passw = GetInp::gp('passw');
        $save = GetInp::has('save');


        if (empty($login) || empty($passw)) {
            $this->tmpl->login_err = $this->tmpl->lang_m_auth_wrong;
        } else {
            $userinfo = $this->model->userInfoLogin($login);
            if (!empty($userinfo) && ($userinfo['password'] == Passw::enc($passw))) {
                $this->save($userinfo['id'], $save); 
            } else {
                $this->tmpl->login_err = $this->tmpl->lang_m_auth_wrong;
            }

        }

        //print_r ($this->varpub);

        //echo Passw::enc('adm2');
    }

    public function save($login_id, $cookie = false, $redir = true)
    {
        Session::set('login_id', $login_id);
        Session::set('token', $this->varpub->token);
        if ($cookie) {
            $passw_cookie = $this->genPasswCookie();
            //die($passw_cookie.'!!!!');
            $this->model->updPasswCookie($login_id, $passw_cookie);
            Cookie::set('login_id', $login_id);
            Cookie::set('passw', $passw_cookie);
        }

        $this->varpub->login_id = $login_id;

        if ($redir) {
            if ($this->varpub->place == 'adm') $page = $this->config['adm'];
            else $page = '';
            Redir::page($page);
        }
    }

    public function getLoginId() 
    {

    }

    public function check()
    {
        $res = false;
        $checkSess = $this->checkSess();
        if ($checkSess) {
            $this->save($checkSess, false, false);
            $res = true;            
        } else {
            $checkCook = $this->checkCook();
            if ($checkCook) {
                $this->save($checkCook, true, false);
                $res = true;
            }
        }
        return $res;
    }

    public function checkSess()
    {
        $res = false;
        $login_id = Session::get('login_id');
        $token_sess = Session::get('token');

        //print_r ($this->model->checkId($login_id));
        //if ($this->varpub->login_id) echo 'ok';
        if ($this->model->checkId($login_id) && ($token_sess == $this->varpub->token)) {
            $res = $login_id;
            //echo 'ok';
        }

        return $res;
    }

    public function checkCook()
    {
        $res = false;
        $login_id = Cookie::get('login_id');
        $passw_cookie = Cookie::get('passw');

        $userinfo = $this->model->userInfoId($login_id);
        if (!empty($userinfo) && ($userinfo['passw_cookie'] == $passw_cookie)) {
            $res = $login_id;
            //echo 'ok';
        }

        return $res;
    }

    private function genPasswCookie()
    {
        $passw = session_id().UserAgent::get().site_path.Ip::get(); 
        $res = hash ('sha256', $passw);
        return $res;
    }
}
