<?php

  include('../db_connect.php');
  include('../config.php');

  //SQL文を作る
  $sql = 'SELECT c.id, c.client_name FROM clients c';

  //プリペアドステートメントを作る
  $statement = $pdo->prepare($sql);

  //SQL文を実行する
  $statement -> execute();

  $lists = [];
  while ($project = $statement->fetch(PDO::FETCH_ASSOC)) {
    $lists[] = $project;
  }

  //変数をクリアにする
  $statement = null;
  $pdo = null;
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

          <h1>クライアント一覧</h1>
          <a href="create.php" class="btn-open">新規登録</a>
          <div class="example_02">
            <table class="table_02">
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 65%;">クライアント名</th>
            </tr>
            <?php foreach ($lists as $list) { ?>
            <tr>
                <td style="width: 5%;"><?= $list['id']; ?></td> 
                <td style="width: 65%;"><a href='view.php?id=<?= $list['id'] ?>'><?= $list['client_name']; ?></a></td>
            </tr>
            <?PHP  } ?>
            </table>
            </div>
        </div>
      </div>
      <?php include('../views/menu.inc.php'); ?>
      <?php include('../views/footer.inc.php'); ?>
  </body>
</html>
