<?php

  include_once('db_connect.php');

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

          <h1>案件一覧</h1>

          <form action="" method="get">
          <!-- get_project_list2.php -->

          <!-- ほんとはDBから引きたい・・・  -->
          <label>
              <select name="status_list">
                      <option value="waiting">未着手</option>
                      <option value="working">進行中</option>
                      <option value="done">完了</option>
                      <option value="billed">請求済</option>
                      <option value="paid">入金確認済</option>
                      <option value="Canceled">中止</option>
              </select>
          </label>

          <input type="submit" value="表示">

        </form>



          <a href="project/create.php" class="btn-open">新規登録</a>

          <table>
            <tr>
              <th>No.</th>
              <th>種別</th>
              <th>案件名</th>
              <th>開始日</th>
              <th>完了日</th>
              <th>請求日</th>
              <th>金額</th>
              <th>状況</th>
              <th>請求先</th>
            </tr>

            <?php
            //include('get_project_list.php');

            //project_typeをテキストに置換
            $project_type_text = [
            "1" => "新規案件",
            "2" => "保守",
            ];

            foreach ($lists as $list) { ?>
              <tr>
                <td><a href='project/view.php?id=<?= $list['id'] ?>'><?= $list['id']; ?></a></td>
                <td><?= $project_type_text[$list['project_type']] ?></td>
                <td><?= $list['project_name']; ?></td>
                <td><?= $list['start_date']; ?></td>
                <td><?= $list['end_date']; ?></td>
                <td><?= $list['billing_date']; ?></td>
                <td><?= $list['amount']; ?></td>
                <td><?= $list['status_text']; ?></td>
                <td><?= $list['client_name']; ?></td>
              </tr>
            <?PHP  } ?>
          </table>

        </div>
      </div>
      <?php include('views/menu.inc.php'); ?>
      <?php include('views/footer.inc.php'); ?>
  </body>
</html>
