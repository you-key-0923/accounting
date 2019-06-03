<?php
/***TO DO
クライアント情報を抽出の箇所関数化したい
疑問符パラメータを、名前パラメーターに
*/


// セッションの開始
session_start();

include('../db_connect.php');
include('../config.php');


/*----------------------------
    入力データを登録
-----------------------------*/

if(!empty($_POST)){
    $sql = 'INSERT clients SET 
    client_name=:name
    ,zip_code=:zip_code
    ,address=:address
    ,tel1=:tel1
    ,tel2=:tel2
    ,staff=:staff
    ,remarks=:remarks
    ,created_at=now()
    ,updated_at=now()';

    $stmt = $pdo->prepare($sql);

    // 値のバインド
    $stmt->bindValue(':name', $_SESSION['join']['client_name'], PDO::PARAM_STR);
    $stmt->bindValue(':zip_code', $_SESSION['join']['zip_code'], PDO::PARAM_STR);
    $stmt->bindValue(':address', $_SESSION['join']['address'], PDO::PARAM_STR);
    $stmt->bindValue(':tel1', $_SESSION['join']['tel1'], PDO::PARAM_STR);
    $stmt->bindValue(':tel2', $_SESSION['join']['tel2'], PDO::PARAM_STR);
    $stmt->bindValue(':staff', $_SESSION['join']['staff'], PDO::PARAM_STR);
    $stmt->bindValue(':remarks', $_SESSION['join']['remarks'], PDO::PARAM_STR);

    // SQLの実行
    $stmt ->execute();
    
    unset($_SESSION['join']);
    header('Location: index.php');
    exit();
}

?>


<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>請求管理ツール</title>
    <link href="../style.css" rel="stylesheet" type="text/css" />
  </head>

  <body>
      <?php include('../views/header.inc.php'); ?>


      <div id="main">
        <div class="inner">

          <h1>クライアント新規登録 確認画面</h1>
          <form action="" method="post">
          <input type="hidden" name="action" value="submit" />
          <table class="table_01">
            <tr><th>クライアント名：</th><td><?= $_SESSION['join']['client_name']; ?></td></tr>
            <tr><th>郵便番号：</th><td><?= $_SESSION['join']['zip_code']; ?></td></tr>            
            <tr><th>住所：</th><td><?= $_SESSION['join']['address']; ?></td></tr>
            <tr><th>電話番号１：</th><td><?= $_SESSION['join']['tel1']; ?></td></tr>
            <tr><th>電話番号２：</th><td><?= $_SESSION['join']['tel2']; ?></td></tr>
            <tr><th>担当者名：</th><td><?= $_SESSION['join']['staff']; ?></td></tr>
            <tr><th>備考：</th><td><?= $_SESSION['join']['remarks']; ?></td></tr>
          </table>
          <input type="submit" class="submit_btn" value="登録">
          </form>

        </div>
      </div>
      <?php include('../views/menu.inc.php'); ?>
      <?php include('../views/footer.inc.php'); ?>
  </body>
</html>
