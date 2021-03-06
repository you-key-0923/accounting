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
  ,p.work_status
  ,p.billing_status
  ,p.remarks
  ,p.created_at
  ,p.updated_at
  ,p.client_id
  ,c.client_name

  FROM projects AS p

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
          <h2>No.</th><td><?= h($project['id']).'【'. h($project_type_text[$project['project_type']]).'】'
. h($project['project_name']); ?></h2>

          <span class="status-<?= h($project['work_status']); ?>"><?= h($status_text[$project['work_status']]); ?></span>
          <span class="status-<?= h($project['billing_status']); ?>"><?= h($status_text[$project['billing_status']]); ?></span>
          <p class="date">作成日時：<?= h($project['created_at']); ?></p>
          <p>更新日時：<?= h($project['updated_at']); ?><p>

          <table class="table_01">
          <!--
          <tr><th>No.：</th><td><?= h($project['id']); ?></td></tr> 
          <tr><th>案件種別：</th><td><?= h($project_type_text[$project['project_type']]); ?></td></tr>
          <tr><th>案件名：</th><td><?= h($project['project_name']); ?></td></tr>
          -->
          <tr><th>開始日：</th><td><?= show_date($project['start_date']); ?></td></tr>
          <tr><th>完了日：</th><td><?= show_date($project['end_date']); ?></td></tr>
          <tr><th>請求日：</th><td><?= show_date($project['billing_date']); ?></td></tr>
          <tr><th>金額：</th><td><?= number_format(h($project['amount'])); ?> 円</td></tr>
          <tr><th>クライアント名：</th><td><?= h($project['client_name']); ?></td></tr>
          <tr><th>備考：</th><td><?= nl2br($project['remarks']); ?></td></tr>
          </table>

          

          <a href="edit.php?id=<?= h($project['id']); ?>" class="submit_btn">編集</a>

        </div>
      </div>
      <?php include('../views/menu.inc.php'); ?>
      <?php include('../views/footer.inc.php'); ?>
  </body>
</html>