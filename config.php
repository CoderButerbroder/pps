<?php

error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

global $database;

// данные основной базы данных
$host = '127.0.0.1';
$port = '3310';
$name_db = 'ivanov-kp-pps';
$encoding_db = 'utf8';
$login_db = 'ivanov-kp-pps';
$password_db = 'nY2lM1eP9b';

// основная база mysql
try {
      if ($port == '')
           {$database = new PDO('mysql:host='.$host.';dbname='.$name_db.';charset='.$encoding_db, $login_db, $password_db, array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_TIMEOUT => '600'));}
      else {$database = new PDO('mysql:host='.$host.';port='.$port.';dbname='.$name_db.';charset='.$encoding_db, $login_db, $password_db, array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_TIMEOUT => '600'));}
      // включение ошибок sql
      $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $database->setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);
      $database->exec("set names utf8");
      unset($host, $port, $name_db, $encoding_db, $login_db, $password_db);
}
catch (PDOException $e) {
      echo json_encode(array('response' => false, 'data' => NULL, 'description' => 'База данных не отвечает, пожалуйста попробуйте позже '.$e->getMessage()),JSON_UNESCAPED_UNICODE);
      die();
      exit;
}