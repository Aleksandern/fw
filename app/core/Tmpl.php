<?php
namespace app\core;

use \app\core\lang\contr\LangGet;

class Tmpl 
{
    // файл главного шаблона
    //protected $tmpl;
    protected $config;
    private $varpub;    
    private $vars = Array();
    private $file;
    // если $debug = false то из шаблона удаляются символы переноса строки и HTML-код получится одной строкой 
    private static $debug = true;
    // если $replace_m = true , то переменные в шаблонах вызываются через {ept} и через $this->ept
    // если false, то только $this->ept
    private static $replace_m = true;

    function __construct () 
    {
        $this->config = \Config::get();
        $this->varpub = \VarPub::get();
        //$this->tmpl = site_path.DIRSEP.$this->config['tmpl'].DIRSEP.$this->config['tmpl_name'].DIRSEP.'index.php';
    }

  /*public function addvar ($var, $value) {
    $this->vars[$var] = $value;
    return $this;
  }*/
  
    public function __set ($var, $value) 
    {
        $this->vars[$var] = $value;
        return $this;
    }
  
    public function __get ($var) 
    {
        //echo 'query: '.' '.$var.'<br>';
        if (array_key_exists($var, $this->vars)) {
            if ($this->vars[$var] instanceof self) {
                return $this->vars[$var]->prepare();
            } else {
                return $this->vars[$var];
            }
        }
        return NULL;    
    }
    //  получить массив переменных
    public function getAllVars () 
    {
        return $this->vars;
    }

    // сохранить массив переменных vars из другого экземпляра этого класса в этот
    public function addOtherVars ($vars) 
    {
        $lang='';

        if ($vars instanceof LangGet) {
            $lang = 'lang_';
            $det_module = $vars->detModule();
            if ($det_module!='') $lang .= 'm_';
            //echo 'asd!!!';
            //echo $det_module;
            //echo $lang.'<br>';
            $vars = $vars->getAll();
        }

        if (!empty($vars)) {
            foreach ($vars as $key => $val) {
                if ($lang!='') {
                    $key = $lang.$key;
                }
                $this->$key = $val;
                
            }
        }
        //print_r ($vars);
    }

    public function setFile ($file) 
    {
        $this->file = $file;
    }

    public function prepare() 
    {
        ob_start();
        ${__CLASS__} = $this;
        //if (file_exists($this->file)) {        
            include $this->file;
        //}
        unset(${__CLASS__});
        if (self::$replace_m) {
            return (self::$debug) ? $this->replVars(ob_get_clean()) : $this->zip($this->replVars(ob_get_clean()));
        } else {
            return (self::$debug) ? ob_get_clean() : $this->zip(ob_get_clean());
        }
        //return $this->repl_vars(ob_get_clean());  
        //return ob_get_clean();  
        //return (self::$debug) ? ob_get_clean() : $this->zip(ob_get_clean());
    }

    public function display () 
    {
        echo $this->prepare();
    }

    private function replVars ($text) 
    {
        // массив для переменных которые совпали
        $matched = Array();


        // если есть форма в шаблоне то добавляем скрытое поле со значением token
        if ($this->config['token']) {
            $text = str_replace('</form>', '<input type="hidden" name="token" value="'.$this->varpub->token.'"></form>', $text);
        }        

        // ищем в шаблоне переменные для замены
        preg_match_all ('/{(.*)}/', $text, $match);

        if (!empty($match[0])) {
            // убираем дубликаты
            $match[1] = array_unique($match[1]);      
            foreach ($this->vars as $varname => $varval) {
                //if (!$varval instanceof tmpl_vi) {
                foreach ($match[1] as $key => $val) {
                    if ($varname==$val) {
                        $matched[] = $varname;
                            if ($varval instanceof self) {
                                //$varval= '!!!';
                                $varval= $this->$varname;
                            }
                        $text = str_replace('{' . $varname . '}', $varval, $text, $count);
                    }
                }
        //}
            }

            // ищем разницу в массивах (массив с совпавшими переменными и массив с указанными переменными в шаблоне)
            $diff = array_diff($match[1], $matched);
            // удаляем переменные из шаблона, которые не используются
            foreach ($diff as $val) {
                $text = str_replace('{' . $val . '}', '', $text);
            }      
        }
        return $text;
    }
    // удаляем всякие переносы и новые строки из html кода
    private function zip ($text) 
    {
        return (empty($text)) ? $text : str_replace (array("\t", "\n", "\r"), '', $text);
    }
}

