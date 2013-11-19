<?php
namespace app\core;

use \PDO;

class Db
{
    private $conn;
    private $select;
    private $host;
    private $user;
    private $pass;
    private static $mode_res = 2; // 1 - для запроса одной строки (LIMIT 1), 2 - возвращает именованный массив
                     
    function __construct() 
    {
        $config = \Config::get();
        $this->select = $config['db']['select'];
        $this->host = $config['db']['host'];  
        $this->user = $config['db']['user'];
        $this->pass = $config['db']['pass'];
      
        try {
            $this->conn = new PDO ("mysql:host=".$this->host.";dbname=".$this->select."", $this->user, $this->pass);
            $this->conn -> setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );
            $this->conn->exec('SET NAMES UTF8');
        }
        catch (PDOException $e) {
            echo $e->GetMessage().' '.FILE_APPEND;
        }
    }

    public function query($request, $data = Array()) 
    {
        $data = $this->strToArr($data);
        $query = $this->conn->prepare($request); 
        $query->execute($data);
    }
    
    public function select($request, $data = Array(), $mode_res = 2) 
    {
        $data = $this->strToArr($data);
        $query = $this->conn->prepare($request);
        $query->execute($data);
        return $this-> resultToArray($query, $mode_res);
        
        //print_r ($hz);
    }

    public function insert ($request, $data) 
    {
        $data = $this->strToArr($data);
        $query = $this->conn->prepare($request);
        $query->execute($data);
        return $this->conn-> lastInsertId();
    }
  
    private function resultToArray(&$query, $mode_res = 2)
    {
        $result = array();
        if($query === false) { return $result; }
      
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $result = $query->fetchAll();

        if (($mode_res == 1) && (!empty($result))) $result = $result[0];
        
        return $result;
    }     

    private function strToArr ($data)
    {
        if (!is_array($data)) {
            $data = str_replace (' ', '', $data);
            $data = explode(',', $data);
        }
        return $data;
    }

    public function quoteIN($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                $val = $this->conn->quote($val);
                $data[$key] = $val;
            }
            $data = implode(',', $data);
        } else {
            $data = explode (',',$data);
            $data = $this->quoteIN($data);
        }

        if (empty($data)) {
            $data = 'NULL';
        }

        return $data;
    }

    /*public function select2 ($request, $data=array()) 
    {
        $hz = $this->prepare($request, $data);
        $query = $this->conn->query($hz);
        return $this->result2array($query);
    }

    private function prepare($request, $data)
    {
        //$query = array_shift($args);

        $request = str_replace("%s","%s",$request);
        //echo $request.'<br><br>';
        foreach ($data as $key => $val) {
            $data[$key] = $this->conn->quote($val);
        }
        $request = vsprintf($request, $data);
        //echo $request.'<br><br>';
        if (!$request) return FALSE;

        $res = $request;
        //$res = mysql_query($query) or trigger_error("db: ".mysql_error()." in ".$query);
        return $res;
  }*/
}
