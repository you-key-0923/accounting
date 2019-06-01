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
  $sql = 'SELECT
   p.id
  ,p.project_type
  ,p.project_name
  ,p.start_date
  ,p.end_date
  ,p.billing_date
  ,p.amount
  ,s.status_text
  ,c.client_name
  FROM projects AS p
  LEFT JOIN status AS s
  ON s.status_en = p.status
  LEFT JOIN clients AS c
  ON c.id = p.client_id

  WHERE p.id = :id';

  //プリペアドステートメントを作る
  $stmt = $pdo->prepare($sql);

  //バインド
  $stmt ->bindValue(':id', $id, PDO::PARAM_INT);

  //SQL文を実行する
  $stmt -> execute();

  $project = $stmt->fetch(PDO::FETCH_ASSOC);

  //変数をクリアにする
  $stmt = null;
  $pdo = null;

  //**** SQL実行して、データ引いてこなかったら（REQUESTしたidの数字がなかったら）
  if(empty($project)){
    header('Location: ../project_list.php');
  }
  

  $project_type_text = [
    "1" => "新規",
    "2" => "保守",
    ];

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

          <h1>案件詳細</h1>

          <p><?= $project['status_text']; ?><p>

          <table class="table_01">
          <tr><th>No.：</th><td><?= $project['id']; ?></td></tr> 
          <tr><th>案件種別：</th><td><?= $project_type_text[$project['project_type']]; ?></td></tr>
          <tr><th>案件名：</th><td><?= $project['project_name']; ?></td></tr>
          <tr><th>開始日：</th><td><?= $project['start_date']; ?></td></tr>
          <tr><th>完了日：</th><td><?= $project['end_date']; ?></td></tr>
          <tr><th>請求日：</th><td><?= $project['billing_date']; ?></td></tr>
          <tr><th>金額：</th><td><?= $project['amount']; ?> 円</td></tr>
          <tr><th>請求先：</th><td><?= $project['client_name']; ?></td></tr>
          </table>

          

          <a href="edit.php?id=<?= $project['id']; ?>" class="submit_btn">案件編集</a>
          <a href="#" class="submit_btn">入金処理</a>

        </div>
      </div>
      <?php include('../views/menu.inc.php'); ?>
      <?php include('../views/footer.inc.php'); ?>
  </body>
</html>