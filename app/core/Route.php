<?php
namespace app\core;

use \app\core\lang\contr\LangCho;
use \app\core\lang\contr\LangGet;
use \app\core\login\contr\Login;

class Route 
{
    private $module_info;
    private $tmpl;
    private $config;
    private $varpub;
    // индексный файл шаблона
    private $file_tmpl;
    // индексный файл шаблона админки
    private $file_tmpl_adm;

    function __construct () 
    {
        //$this->dir_module = site_path.DIRSEP.'module';
        $this->config = \Config::get();
        $this->varpub = \VarPub::get();

        $token = new Token();
        $token->gen();
        $token->check();

        $this->file_tmpl = site_path.DIRSEP.$this->config['tmpl'].DIRSEP.$this->config['tmpl_name'].DIRSEP.'index.php';
        $this->file_tmpl_adm = site_path.DIRSEP.'app'.DIRSEP.'adm'.DIRSEP.'tmpl'.DIRSEP.'first'.DIRSEP.'index.php';
        $this->tmpl = new Tmpl();

        $this->tmpl->sitedom=$this->config['site_dom'];
        $this->tmpl->hzhz = 'hzhz';
      
    }


    public function start() 
    {
        //$routes = explode('/', $_SERVER['REQUEST_URI']);
        $data_disp['module'] = Array();
        $data_disp['article'] = Array();
        $modules_res = Array();
        $modules_res['item'] = Array();
        $modules_res['vars_tmpl_main'] = Array();

        $uri = parse_url($_SERVER['REQUEST_URI']);
        $uri['path'] = ltrim($uri['path'], '/');
        $routes = explode('/', $uri['path']);
        $adm = in_array('adm', $routes);



   //setcookie ('timedcockie', 'value123', time()+3600, '/', 'fw', false, true);
   //setcookie ('timedcockie', 'value123', time()-3600, '/', 'fw', false, true);

   // setcookie ('adm', 'adm123', time()+3600, '/adm', 'fw', false, true);

    //setcookie ('timedcockie', 'value123', time()-3600, '/12');
  //  unset ($_SESSION['timedcockie']);
        //Cookie::set('123','321');
        //Cookie::set('321','321');
        //Cookie::del('321');
        //echo Cookie::get('123');
        
        //Cookie::delAll();
        //setcookie('000', '111', time()-3600);
        //setcookie('123', '111', time()-3600);

        




    //print_r ($uri);
    //print_r ($routes);
    
    //print_r ($_COOKIE);

//$signature=hash_hmac('sha1',$body,$auth_secret);
//    echo hash_hmac('sha1','body','secr');
    //echo $_SERVER['HTTP_HOST'];

        // устанавливаем в глобальные переменные где находимся, в админке или нет
        if ($adm) $this->varpub->place = 'adm';
        else $this->varpub->place = 'public';

        //echo $_SERVER['HTTP_REFERER'];
        // определяем, какой языковой файл подлючать
        $lang = new LangCho($adm, $routes);
        $routes = $lang->select();

        //echo $this->varpub->lang.'<br>';
        $langget = new LangGet();
        //echo $langget->hzhz;
        //echo '<br>'.$langget->hz;

        //$this->tmpl->addOtherVars($langget->getAll(), 'lang', $langget);
        $this->tmpl->addOtherVars($langget);
    
        // работа с админкой
        if ($adm) {
            //$this->tmpl-> setFile ($this->file_tmpl_adm); 
            $this->varpub->tmpl = $this->file_tmpl_adm;

            $this->module_info['dir'] = site_path.DIRSEP.'app'.DIRSEP.'adm'.DIRSEP.'modules';
            $this->module_info['namespace'] = '\\app'.'\\adm'.'\\modules';

            $login = new Login();
            $login->actionIndex(false);
            $login_check = $login->check();            

            if ($login_check) {
                    
                // если в адресной строке есть что-то после adm, то переходим к этому
                if (isset($routes[1])&&!empty($routes[1])) {
                    echo 'Administration';
                }
                // если в адресной строке только adm, то открываем форму авторизации или админку
                else {
                    echo 'login';
                }
            } else {
                $this->module_info['dir'] = site_path.DIRSEP.'app'.DIRSEP.'core';
                $this->module_info['namespace'] = '\\app'.'\\core';
                $data_disp['module'][]= 'login';
            }
            unset ($login);
            unset ($login_check);

        }
        // работа с сайтом
        else {
            //$this->tmpl-> setFile ($this->file_tmpl); 
            $this->varpub->tmpl = $this->file_tmpl;
            $this->module_info['dir'] = site_path.DIRSEP.'modules';
            $this->module_info['namespace'] = '\\modules';

            $data_disp['module'][]= 'main';
            $data_disp['module'][]= 'portfolio';
        }



        // проходимся по массиву с названиями модулей и статьи
        foreach ($data_disp as $data_type => $item_arr) {
            foreach ($item_arr as $item) {
        
                // метод, который запускается по-умолчанию в модуле
                $action_name = 'Index';
                //echo $item.'<br>';
                // подключаем конкроллеры и модули в зависимости от выбранного типа
                switch ($data_type) {
                    case 'module':
                        // добавляем префиксы
                        $model_name = $item;
                        $controller_name = ucfirst($item);
                        $action_name = 'action'.$action_name;

            // подцепляем файл с классом модели (файла модели может и не быть)
            /*$model_file = $model_name.'.php';
            $model_path = $this->dir_module.DIRSEP.$item.DIRSEP.$model_file;
            if(file_exists($model_path)) {
              require_once $model_path;
            }*/



            // подцепляем файл с классом контроллера
            /*$controller_file = $controller_name.'.php';
            $controller_path = $this->dir_module.DIRSEP.$item.DIRSEP.$controller_file;
            if(file_exists($controller_path)) {
                require_once $controller_path;
            }
            else {
              //die('Wrong Controller!');
            }*/
    /*if ($item=='portfolio'){
            
      $asd = new \model_portfolio();

      print_r ($asd->get_data());
    }*/

            // создаем контроллер
            //echo '<br>- '.$controller_name.' -<br>';

            //$hzhz  = str_replace('\\', DIRECTORY_SEPARATOR, $this->d_module) . DIRECTORY_SEPARATOR.'main'.'\\';
            //echo $hzhz.'<br>';

                        $controller = $this->module_info['namespace'].'\\'.$item.'\\'.'contr'.'\\'.$controller_name;
                        //echo '__/ '.$controller.' \__';
                        $controller = new $controller();
                        $action = $action_name;
        
                        if(method_exists($controller, $action)) {
                            // вызываем действие контроллера
                            $controller->$action();

                            // собираем результат выплонения модулей в отедельный массив
                            $modules_res['item'][$item] = $controller;
                            // собираем переменные, предназначенный для главного шаблона
                            $modules_res['vars_tmpl_main'][] = $controller->getVarsTmplMain();
                            //$tmpl_vi_vars = $this->tmpl_vi->get_all_vars();
                            //$this->tmpl_vi->addvar($item,$controller->get_info_tmpl($tmpl_vi_vars));
                
                        }
                        else {
                            // здесь также разумнее было бы кинуть исключение
                            die('Method of the Controller doesnt exist!');
                            // $this->ErrorPage404();
                            //$modules_res[$item]= $controller;
                        }
                        break;
                    case 'article':

                        break;
                    default:
                        break;
                }
        
            }
        }


        // кладём переменный из модуля, предназначенные для главного шаблона, в главный шаблон
        // и также эти переменные попадают в другие модули
        foreach ($modules_res['vars_tmpl_main'] as $key => $vars) {
            $this->tmpl->addOtherVars($vars);
        }

        // получаем все переменные vars из шаблона
        $tmpl_vars = $this->tmpl->getAllVars();
          //print_r ($modules_res);    
        // шерстим массив с модулями и добавляем их в главный шаблон
        foreach ($modules_res['item'] as $module => $obj) {
            $module_ret = $obj->getInfoTmpl($tmpl_vars);
            $this->tmpl->$module = $module_ret['tmpl'];
        }
    
        //print_r ($this->tmpl_vi->vars);
        $this->tmpl-> setFile ($this->varpub->tmpl);         
        $this->tmpl->display();


        print_r ($_SESSION);
        echo '<hr>';
        print_r ($_COOKIE);
        echo '<hr>';        
    }  
  
    
    private function ErrorPage404() 
    {
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('HTTP/1.1 404 Not Found');
        header("Status: 404 Not Found");
        header('Location:'.$host.'404');
    }
}
