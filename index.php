<?php

  include('db_connect.php');
  include('config.php');

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

  FROM
  projects AS p

  LEFT JOIN
  status AS s
  ON s.status_en = p.status
  LEFT JOIN
  clients AS c
  ON c.id = p.client_id

  ORDER BY p.id';

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
    <link href="style.css" rel="stylesheet" type="text/css" />
  </head>

  <body>
      <?php include('views/header.inc.php'); ?>


      <div id="main">
        <div class="inner">

          <h1>トップページ！！</h1>
          <div class="index_box">
            <p>アラートを出すのだ！</p>
            <ul>
                <li>未請求の案件が、「」件あるよ！</li>
                <li>現在進行中の案件は「」件だよ！</li>
            </ul>
            </div>

            <table border="1">
                <tr>
                    <th>未着手</th>
                    <th>進行中</th>
                    <th>完了</th>
                    <th>請求済</th>
                    <th>入金確認済</th>
                    <th>中止</th>
                </tr>
                <tr>
                    <td>0件</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>

                </tr>
            </table>
        </div>
      </div>
      <?php include('views/menu.inc.php'); ?>
      <?php include('views/footer.inc.php'); ?>
  </body>
</html>
