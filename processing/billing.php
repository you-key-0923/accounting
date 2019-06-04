<?php
//一括処理したかったけど、できなかったやつ。。。

include('../db_connect.php');
include('../config.php');


var_dump($_POST['check']);

//選択したIDを取り出す
if (isset($_POST['check']) && is_array($_POST['check'])) {
    $id = implode(",", $_POST["check"]);
}
$id = '(' . $id . ')';


  
/*----------------------------
    登録データ読み出し
-----------------------------*/

//SQL文を作る
$sql = 'SELECT
p.id
,p.project_type
,p.project_name
,p.billing_date
,p.amount
,p.work_status
,p.billing_status
,c.client_name

FROM
projects AS p

LEFT JOIN
clients AS c
ON c.id = p.client_id

WHERE p.id IN :id';




//プリペアドステートメントを作る
$stmt = $pdo->prepare($sql);

//バインド
$stmt ->bindValue(':id', $id, PDO::PARAM_STR);

//SQL文を実行する
$stmt -> execute();

var_dump($stmt);

$lists = [];
while ($project = $stmt->fetch(PDO::FETCH_ASSOC)) {
  $lists[] = $project;
}

$_POST = null;

var_dump($lists);





/*----------------------------
  入力内容をUPDATE
-----------------------------*/
if(!empty($_POST['billing_update'])){

//更新データ格納用の変数の用意
$update = [];
$update = $_POST;

// idは別途取得しておく
$update_id = $_POST['id'];
//var_dump($update);

//UPDATE用のSQLの作成
$sql = 'UPDATE projects SET 
billing_date=:billing_date
,amount=:amount
,billing_status=:billing_status
,remarks=:remarks
,updated_at=now()

WHERE id=:id';

$stmt = $pdo->prepare($sql);

// 値のバインド
$stmt->bindValue(':id', $update_id, PDO::PARAM_INT);
$stmt->bindValue(':billing_date', $update['billing_date'], PDO::PARAM_STR);
$stmt->bindValue(':amount', $update['amount'], PDO::PARAM_INT);
$stmt->bindValue(':billing_status', $update['billing_status'], PDO::PARAM_STR);
$stmt->bindValue(':remarks', $update['remarks'], PDO::PARAM_STR);

// SQLの実行
$stmt ->execute();

//変数をクリアにする
$stmt = null;
$pdo = null;

//header('Location: view.php?id='.$id);
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

          <h1>一括請求済み処理</h1>
          
          <form action="" method="post">
          <input type="hidden" name="action" value="submit" />
          
          <div class="box28">
          請求日<input type="date" name="billing_date">
          <input type="submit" name="billing_update" value="請求済みにする">
         </div>

         
          <table class="table_01">
                <tr>
                    <th>No</th>
                    <th>案件名</th>
                    <th>クライアント名</th>
                    <th>金額</th>
                </tr>

                <?php foreach ($lists as $list) { ?>
              <tr>
                <td><?= $list['id']; ?></td>
                <td><?= $list['project_name']; ?></td>
                <td><?= $list['client_name']; ?></td>
                <td><?= $list['amount']; ?></td>
              </tr>
            <?PHP  } ?>
            </table>
          
          </form>

        </div>
      </div>
      <?php include('../views/menu.inc.php'); ?>
      <?php include('../views/footer.inc.php'); ?>
  </body>
</html>
