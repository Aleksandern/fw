<?php
error_reporting (E_ALL);

set_time_limit(0);

if (version_compare(phpversion(), '5.3.0', '<') == true) { die ('You need PHP 5.3 or later.'); }
//print_r (spl_autoload_functions());

define ('DIRSEP', DIRECTORY_SEPARATOR); 
define ('site_path', __DIR__);

ini_set('session.cookie_lifetime', 0); // после закрытия барузера куки будут удалены
ini_set('session.gc_maxlifetime', 2592000);  // время жизни, после которого жанные будут мусором и будут удалены
ini_set('session.gc_divisor', 100); // запуск функции сборки мусора
ini_set('session.gc_probability', 1); // запуск функции сборки мусора
session_start();

// подключаем файл с классом в глобальный код для получения данных из конфига
require_once site_path.DIRSEP.'app'.DIRSEP.'core'.DIRSEP.'Config.php';
// подключаем файл с классов для работы с глобальными переменными
require_once site_path.DIRSEP.'app'.DIRSEP.'core'.DIRSEP.'VarPub.php';


require_once site_path.DIRSEP.'app'.DIRSEP.'Bootstrap.php';

//set_include_path( get_include_path() . PATH_SEPARATOR . site_path);
//spl_autoload_extensions(".php"); // comma-separated list
//spl_autoload_register();
//require_once('hz/ihz.php');

//use \nhz;

//$hz = new hz\hz2\ihz();
//echo $hz->zzh() . ' -';

?>
