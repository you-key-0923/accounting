<?php

  include('db_connect.php');
  include('config.php');

  //SQL文を作る
  $sql = 
  "SELECT
  DATE_FORMAT(p.billing_date, '%Y-%m') month
  ,COUNT(p.billing_status = 'unbilled' OR NULL) unbilled_count
  ,SUM(CASE p.billing_status WHEN 'unbilled' THEN p.amount ELSE 0 END) unbilled_sum
  ,COUNT(p.billing_status = 'billed' OR NULL) billed_count
  ,SUM(CASE p.billing_status WHEN 'billed' THEN p.amount ELSE 0 END) billed_sum
  ,COUNT(p.billing_status = 'paid' OR NULL) paid_count
  ,SUM(CASE p.billing_status WHEN 'paid' THEN p.amount ELSE 0 END) paid_sum
  ,COUNT(p.id) total_count
  ,SUM(p.amount) total_sum
  
FROM
  projects p
GROUP BY
  DATE_FORMAT(p.billing_date, '%Y%m');";

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

          <h1>summary</h1>
            <p>※月次毎の請求額（作業ステータスは条件に含めず）
            <table class="list">
                <tr>
                    <th>月次</th>
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
                <td><?= h($list['month']); ?></td>
                <td style="text-align: right;"><?= number_format(h($list['unbilled_count'])); ?></td>
                <td style="text-align: right;"><?= number_format(h($list['unbilled_sum'])); ?></td>
                <td style="text-align: right;"><?= number_format(h($list['billed_count'])); ?></td>
                <td style="text-align: right;"><?= number_format(h($list['billed_sum'])); ?></td>
                <td style="text-align: right;"><?= number_format(h($list['paid_count'])); ?></td>
                <td style="text-align: right;"><?= number_format(h($list['paid_sum'])); ?></td>
                <td style="text-align: right;"><?= number_format(h($list['total_count'])); ?></td>
                <td style="text-align: right;"><?= number_format(h($list['total_sum'])); ?></td>
              </tr>
            <?PHP  } ?>
            </table>
        </div>
      </div>
      <?php include('views/menu.inc.php'); ?>
      <?php include('views/footer.inc.php'); ?>
  </body>
</html>
