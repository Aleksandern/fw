<?php
namespace aaa;

define ('DIRSEP', DIRECTORY_SEPARATOR); 
define ('site_path', __DIR__);



/*spl_autoload_register(function($className){
      echo $className;
  require_once str_replace(array('\\','_'),'/',$className).'.php';
});*/

require_once ('app/core/Db.php');
require_once ('app/core/Config.php');

//$dbb = new \app\core\Db();

$config = \Config::get();
$db_host = $config['db']['host'];
$db_user = $config['db']['user'];
$db_pass = $config['db']['pass'];
$db_select = $config['db']['select'];


$db_conn = mysql_connect($db_host, $db_user, $db_pass) OR DIE ("Can't connect to the base.");

// создание базы из переменной $db_select
$query = "CREATE DATABASE IF NOT EXISTS $db_select";
$result = mysql_query ($query) or die ("Error create BD");
echo "<center><p><b>BD '".$db_select."' Created!</b></p></center>'";

// подчлючаемся к созданной базе
mysql_select_db($db_select, $db_conn) or die(mysql_error());

// создание таблицы "adm_users"
$table = 'adm_users';
$sql="
CREATE TABLE IF NOT EXISTS `".$table."` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(64) NOT NULL,
  `password` varchar(255) NOT NULL,
  `passw_cookie` varchar(255) NOT NULL,
  `date` int(10) NOT NULL DEFAULT '0',  
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;";

if(!mysql_query($sql)) echo '<center><p><b>Error create table "'.$table.'"!</b></p></center>'; 
else echo '<center><p><b>Table "'.$table.'" created!</b></p></center>';
