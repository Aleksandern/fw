<?php
namespace app\core\lang\contr;

class LangGet
{
    private $varpub;
    private $config;    
    private $lang_file;
    private $addvar = Array();
    private $module;
    private $path;

  
    function __construct($module = Array())
    {
        $this->varpub = \varpub::get();
        $this->config = \config::get();                

        if (!empty($module)) {
            $this->module = strtolower ($module['name']);
            $this->path = $module['dir'];

        } else {
            $this->module = '';
            $this->path = site_path.DIRSEP.'app'.DIRSEP.$this->varpub->place;
        }
        $this->getFile();
    }

    private function getFile($addvar = array()) 
    {
        if (!$this->lang_file) {
            $this->addvar = array();
            if (!empty($addvar)) {
                $lang_set = $addvar['lang_set'];
            } else {
                $lang_set = $this->varpub->lang;
            }

            $lang_file = $this->path.DIRSEP.'lang'.DIRSEP.$lang_set.'.php';
            if (file_exists($lang_file)) {
                $this->lang_file = include($lang_file);            
            }

            $this->addvar['lang_set'] = $lang_set;
        }
    }

    public function getAll () {
        return $this->lang_file;
    }

    public function detModule () {
        return $this->module;
    }

    public function __get ($var)
    {
        $res = '';
        if (isset($this->lang_file[$var])) $res = $this->lang_file[$var];
        else {
            $addvar = $this->addvar;
            // если переменной не оказалось, от ищеём в языковом файле, который установлен по-умолчанию
            if ($addvar['lang_set'] != $this->config['lang']) {
                $addvar['lang_set'] = $this->config['lang'];
                $lang_backup = $this->lang_file;
                $this->lang_file = null;                
                $this->getFile ($addvar);

                if ($this->lang_file[$var]) $res = $this->lang_file[$var];

                $this->lang_file = $lang_backup;
            }
        }

        return $res;
    }

    
}



