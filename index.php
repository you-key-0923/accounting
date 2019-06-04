<?php

  include('db_connect.php');
  include('config.php');

  //SQL文を作る
  $sql = 
  "SELECT
  c.id
  ,c.client_name
  ,COUNT(p.billing_status = 'unbilled' OR NULL) unbilled_count
  ,SUM(CASE p.billing_status WHEN 'unbilled' THEN p.amount ELSE 0 END) unbilled_sum
  ,COUNT(p.billing_status = 'billed' OR NULL) billed_count
  ,SUM(CASE p.billing_status WHEN 'billed' THEN p.amount ELSE 0 END) billed_sum
  ,COUNT(p.billing_status = 'paid' OR NULL) paid_count
  ,SUM(CASE p.billing_status WHEN 'paid' THEN p.amount ELSE 0 END) paid_sum
  ,COUNT(p.id) total_count
  ,SUM(p.amount) total_sum

  FROM projects p

  LEFT JOIN clients c
  ON c.id = p.client_id

  GROUP BY
  c.id";

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
          <p><a href="https://github.com/you-key-0923/accounting">コードはこちら</a></p>
          <div class="index_box">
            <p>アラートを出す？？</p>
            <ul>
                <li>未請求の案件が、「」件あるよ！とか？</li>
                <li>現在進行中の案件は「」件だよ！とか？</li>
                <li>仕様決まったら作るよ</li>
            </ul>
            </div>
            <p>※クライアント（請求先）毎の請求額（作業ステータスは条件に含めず）
            <table class="list">
                <tr>
                    <th>No</th>
                    <th>クライアント名</th>
                    <th>未請求件数</th>
                    <th>未請求金額</th>
                    <th>請求済件数</th>
                    <th>請求済金額</th>
                    <th>入金確認済件数</th>
                    <th>入金確認済金額</th>
                    <th>合計件数</th>
                    <th>合計金額</th>
                </tr>

                <?php foreach ($lists as $list) { ?>
              <tr>
                <td><?= $list['id']; ?></td>
                <td><?= $list['client_name']; ?></td>
                <td><?= number_format($list['unbilled_count']); ?></td>
                <td><?= number_format($list['unbilled_sum']); ?></td>
                <td><?= number_format($list['billed_count']); ?></td>
                <td><?= number_format($list['billed_sum']); ?></td>
                <td><?= number_format($list['paid_count']); ?></td>
                <td><?= number_format($list['paid_sum']); ?></td>
                <td><?= number_format($list['total_count']); ?></td>
                <td><?= number_format($list['total_sum']); ?></td>
              </tr>
            <?PHP  } ?>
            </table>
        </div>
      </div>
      <?php include('views/menu.inc.php'); ?>
      <?php include('views/footer.inc.php'); ?>
  </body>
</html>
