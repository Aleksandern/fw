<?php
namespace app\core\module\contr;

use \app\core\Tmpl;
use \app\core\lang\contr\LangGet;

class Module 
{
    // класс шаблона
    protected $tmpl;
    protected $tmpl_main;
    protected $config;
    protected $varpub;
    protected $module;  
    protected $module_dir;
    protected $model;

    // шаблонный файл модуля
    protected $tmpl_module_file;    

    function __construct($module = '') 
    {
        $this->config = \Config::get();
        $this->varpub = \VarPub::get();
        
        // определяем название класса если не передано конструктору
        if ($module=='') {
            $module = get_called_class();
        }
        // вырезаем название пространства имён, еслои оно присутствует в слове    
        if ($last_sl = strripos($module, '\\')) {
            $namespace = substr($module, 0, $last_sl);
            $module = substr($module, $last_sl + 1);
        }

        $this->module = $module;

        //подключаем модель, если есть
        $model = $module;
        $model_ns = '\\'.str_replace('contr', 'model', $namespace).'\\'.$model;    
        $file_model = site_path.str_replace('\\',DIRSEP,$model_ns).'.php';
        if (file_exists($file_model)) {
            require_once $file_model;
            if (class_exists($model_ns, false)) {
                $this->model = new $model_ns();
            }
        }

        $this->module_dir = str_replace('\\model\\'.$model.'.php','',$file_model);        


        // подключаем шаблон модуля
        $this->tmpl_module_file = '\\'.str_replace('contr', 'view', $namespace).'\\'.$module;    
        $this->tmpl_module_file = site_path.str_replace('\\',DIRSEP,$this->tmpl_module_file ).'.php';
        $this->tmpl = new Tmpl();
        $this->tmpl-> setFile($this->tmpl_module_file);

        $this->tmpl_main = new Tmpl();


        // добавляем языковой файл модуля в шаблон модуля
        $langget = new LangGet(Array('name' => $module, 'dir' => $this->module_dir));
        $this->tmpl->addOtherVars($langget);        

    }
    
    // метод возращает объект класса шаблона
    public function getInfoTmpl ($vars_other = Array()) 
    {
        if (!empty($vars_other)) {
            $this->tmpl->addOtherVars($vars_other);
        }

        $ret_arr['tmpl'] = $this->tmpl;
        return $ret_arr;    
    }

    public function getVarsTmplMain () 
    {
        return $this->tmpl_main->getAllVars();
    }

}

