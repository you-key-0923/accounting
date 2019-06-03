<?php

  include('../db_connect.php');
  include('../config.php');


  //REQUESTがあるかどうか判断！
  if(isset($_REQUEST['id'])) {
        $id = $_REQUEST['id'];
    }else{
    header('Location: ../project_list.php');
  }

  //SQL文を作る
  $sql = 'SELECT * FROM clients c WHERE c.id = :id';

  //プリペアドステートメントを作る
  $stmt = $pdo->prepare($sql);

  //バインド
  $stmt ->bindValue(':id', $id, PDO::PARAM_INT);

  //SQL文を実行する
  $stmt -> execute();

  $client = $stmt->fetch(PDO::FETCH_ASSOC);

  //変数をクリアにする
  $stmt = null;
  $pdo = null;

  //**** SQL実行して、データ引いてこなかったら（REQUESTしたidの数字がなかったら）
  if(empty($client)){
    header('Location: index.php');
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

          <h1>クライアント詳細</h1>

          <table class="table_01">
          <tr><th>No：</th><td><?= $client['id']; ?></td></tr>
          <tr><th>クライアント名：</th><td><?= $client['client_name']; ?></td></tr>
          <tr><th>郵便番号：</th><td>〒<?= $client['zip_code']; ?></td></tr>
          <tr><th>住所：</th><td><?= $client['address']; ?></td></tr>
          <tr><th>電話番号１：</th><td><?= $client['tel1']; ?></td></tr>
          <tr><th>電話番号２：</th><td><?= $client['tel2']; ?></td></tr>
          <tr><th>担当者名：</th><td><?= $client['staff']; ?></td></tr>
          <tr><th>備考：</th><td><?= $client['remarks']; ?></td></tr>
          </table>
          <a href="edit.php?id=<?= $client['id']; ?>" class="submit_btn">編集</a>

        </div>
      </div>
      <?php include('../views/menu.inc.php'); ?>
      <?php include('../views/footer.inc.php'); ?>
  </body>
</html>