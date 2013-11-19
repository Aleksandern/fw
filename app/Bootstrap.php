<?php

//$core_path = site_path.DIRSEP.'app'.DIRSEP.'core';
//set_include_path( get_include_path() . PATH_SEPARATOR . $core_path);

//spl_autoload_extensions(".php");
//spl_autoload_register();

spl_autoload_register(
    function ($className){
        //echo $className.' -<br>';
        $className = ltrim($className, '\\');
        $fileName  = '';
        $namespace = '';

        if ($lastNsPos = strripos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;

        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

        //$fileName .= $className . '.php';
        //echo '<br>'.$className.' :<br>'.$fileName.'<hr>';    
        //echo site_path.DIRSEP.$fileName;
        $file_path = site_path.DIRSEP.$fileName;
        //if (file_exists($file_path)) 
        require_once $file_path;
    }
);
  
use \app\core\Route;

//$config = \app\core\controller\config_co::get();

$route = new Route();
$route->start();

//use \app\core\controller\config;

//$hz = 'hzhzhz';
//$config = config::get();
//echo $config['site_dom'];
//$conf = config::get();
//$conf->setProperty('name','Ivan');
//unset ($conf);
//$conf2 = config::get();
//$hz = $conf2->getProperty('name');
//$hz2 = $conf2->get();
//echo $conf2['site_dom'];


