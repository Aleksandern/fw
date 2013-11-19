<?php
namespace app\core\lang\contr;

use \app\core\Cookie;

class LangCho
{
    private $uri;
    private $config;
    private $varpub;
    private $lang_dir;
    private $lang_set;
    const COOK_NAME = 'lang'; // также отредактировать в \app\core\Cookie::getPlace()

    function __construct ($adm, $uri) 
    {
        $this->config = \Config::get();
        $this->varpub = \VarPub::get();
        $this->uri = $uri;
        $this->lang_set = $this->config['lang'];

        if ($adm) $dir = 'adm';
        else $dir = 'public';
        $this->lang_dir = site_path.DIRSEP.'app'.DIRSEP.$dir.DIRSEP.'lang';
        //Cookie::set('lang', 'eng');
        //Cookie::delAll();
        //$this->select();


    }

    public function select () {

        $lang_cook = Cookie::get(self::COOK_NAME);
        $lang_cook = strtolower($lang_cook);        
        $uri_lang = $this->uri[0];
        $uri_lang = strtolower($uri_lang);

        if (empty($uri_lang)) {
            $this->checkCook ($lang_cook);
        } else {
            //if (in_array($uri_lang, $this->getList())) {
            if ($this->checkFile($uri_lang)) {
                if (empty($lang_cook)) $this->set($uri_lang);
                if ($lang_cook!=$uri_lang) $this->set($uri_lang);
                else $this->lang_set = $uri_lang;
            } else {
                if (empty($lang_cook)) $this->set($this->config['lang']);
                else {
                    if ($this->checkFile($lang_cook)) {
                        //redirect
                        //echo $_SERVER['REQUEST_URI'].'<br>';
                        //print_r ($this->uri);
                        $this->redirPage ($lang_cook, true);
                    }
                }
            }
        }
        $this->varpub->lang = $this->lang_set;

        $uri_new = array_diff ($this->uri, $this->getList());
        $uri_new = array_values($uri_new);
        return $uri_new;
    }

    private function set ($lang)
    {
        if (!$this->checkFile($lang)) {
            $lang = $this->config['lang'];
        }

        Cookie::set(self::COOK_NAME, $lang);        
        $this->lang_set = $lang;
    }

    // проверка наличия языкового файла
    private function checkFile ($lang) 
    {
        $lang = strtolower($lang);
        if (file_exists($this->lang_dir.DIRSEP.$lang.'.php')) return true;
        return false;
    }

    // список языков
    private function getList () 
    {
        $res = Array ();
        $ext = 'php';
        $mask = $this->lang_dir.DIRSEP.'*.'.$ext;
        foreach (glob($mask) as $var) {
            $var = basename($var);
            $var = str_replace ('.'.$ext, '', $var);
            $res[] = $var;
        }
        return $res;
    }

    private function checkCook ($lang_cook) 
    {
        if (empty($lang_cook)) {
            $this->set($this->config['lang']);
        } else {
            if ($lang_cook != $this->config['lang']) {
                if ($this->checkFile($lang_cook)) {
                    //$this->set($lang_cook);
                    // redirect
                    $this->redirPage($lang_cook);
                }
                else {
                    $this->set($this->config['lang']);
                }
            } else $this->lang_set = $lang_cook;
        }
    }

    private function redirPage ($lang, $save_uri= false)
    {
        $uri = '';
        if ($save_uri) $uri = '/'.implode('/', $this->uri);
        header ('Location: '.$this->config['site_dom'].'/'.$lang.$uri);
        die();
    }
}
