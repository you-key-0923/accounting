<?php
//DB接続情報をまとめるやつを作成中

//DBの接続情報
$host = "localhost";
$user = "root";
$password = "1234";
$name = "accounting";
//MySQLのDSN文字列
$dsn = "mysql:host={$host}; dbname={$name}; charset=utf8";

try{
  $pdo = new PDO($dsn,$user,$password); //接続しただけの状態
}catch(PDOException $e){
  echo 'DB接続エラー:' . $e->getMessage();
}






/*
define('DB_USERNAME','root');
define('DB_PASSWORD','1234');
define('DSN','mysql:host=localhost; dbname=accounting; charset=utf8');

function db_connect(){
    $dbh = new PDO(DSN, DB_USERNAME, DB_PASSWORD);
    return $dbh;
}


try {
    // データベースに接続
    $dbh = db_connect();

    //例外処理を投げるようにする（throw）
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'SELECT * FROM projects';

    $dbn->prepare($sql);

    $statement -> execute();

    $lists = [];
    while ($project = $statement->fetch(PDO::FETCH_ASSOC)) {
      $lists[] = $project;
    }

print_r($lists);

    //データベース接続切断
    $statement = null;
    $dbh = null;

} catch (PDOException $e) {
    header('Content-Type: text/plain; charset=UTF-8', true, 500);
    // エラー内容は本番環境ではログファイルに記録して， Webブラウザには出さないほうが望ましい
    exit($e->getMessage());
}


*/
