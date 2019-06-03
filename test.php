<?php
//DB接続情報をまとめるやつを作成中

function db_connect(){
  //DBの接続情報
$host = "localhost";
$user = "root";
$password = "1234";
$name = "accounting";
//MySQLのDSN文字列
$dsn = "mysql:host={$host}; dbname={$name}; charset=utf8";

try{
  $pdo = new PDO($dsn,$user,$password);
}catch(PDOException $e){
  echo 'DB接続エラー:' . $e->getMessage();
  exit;
}
return $pdo;
}





