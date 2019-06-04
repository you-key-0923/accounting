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
    クライアント情報を抽出
-----------------------------*/

$client_id = $_SESSION['join']['p_client'];

$sql = 'SELECT id,client_name FROM clients WHERE id=:id';
$stmt = $pdo->prepare($sql);
$stmt ->bindValue(':id', $client_id, PDO::PARAM_INT);
$stmt -> execute();

$client = $stmt->fetch(PDO::FETCH_ASSOC);


/*----------------------------
    入力データを登録
-----------------------------*/
if(!empty($_POST)){
    $sql = 'INSERT INTO projects SET 
    project_type=?, 
    project_name=?, 
    start_date=?, 
    end_date=?,
    billing_date=?,
    amount=?,
    work_status=?,
    billing_status=?,
    remarks=?,
    client_id=?,
    created_at=now()';

    $stmt = $pdo -> prepare($sql);

    $stmt->execute(array(
        $_SESSION['join']['p_type'],
        $_SESSION['join']['p_name'],
        $_SESSION['join']['p_start_date'],
        $_SESSION['join']['p_end_date'],
        $_SESSION['join']['p_billing_date'],
        $_SESSION['join']['p_amount'],
        $_SESSION['join']['p_work_status'],
        $_SESSION['join']['p_billing_status'],
        $_SESSION['join']['p_remarks'],
        $_SESSION['join']['p_client']
    ));

    unset($_SESSION['join']);
    header('Location: ../project_list.php');
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

          <h1>案件新規登録 確認画面</h1>
          <form action="" method="post">
          <input type="hidden" name="action" value="submit" />
          <table class="table_01">
           <tr><th>ステータス：</th><td><?= $status_text[$_SESSION['join']['p_work_status']]; ?>　<?= $status_text[$_SESSION['join']['p_billing_status']]; ?></td></tr>
            <tr><th>案件種別：</th><td><?= $project_type_text[$_SESSION['join']['p_type']]; ?></td></tr>
            <tr><th>案件名：</th><td><?= $_SESSION['join']['p_name']; ?></td></tr>
            <tr><th>開始日：</th><td><?= $_SESSION['join']['p_start_date']; ?></td></tr>
            <tr><th>完了日：</th><td><?= $_SESSION['join']['p_end_date']; ?></td></tr>
            <tr><th>請求日：</th><td><?= $_SESSION['join']['p_billing_date']; ?></td></tr>
            <tr><th>金額：</th><td><?= number_format($_SESSION['join']['p_amount']); ?> 円</td></tr>
            <tr><th>請求先：</th><td><?= $client['client_name']; ?></td></tr>
            <tr><th>備考：</th><td><?= $_SESSION['join']['p_remarks']; ?></td></tr>
          </table>
          <input type="submit" class="submit_btn" value="登録">
          </form>

        </div>
      </div>
      <?php include('../views/menu.inc.php'); ?>
      <?php include('../views/footer.inc.php'); ?>
  </body>
</html>
