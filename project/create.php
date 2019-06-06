<?php 
/***TO DO
クライアント情報を抽出の箇所関数化したい
ステータスのリストをDB参照にしたい
*/

include_once('../db_connect.php');
include('../config.php');

session_start();
$_SESSION['join'] = $_POST;

if(!empty($_POST['datapost'])){
header('Location: confirm.php');
}


/*----------------------------
    クライアント情報を抽出
-----------------------------*/
$sql = 'SELECT id,client_name FROM clients';
$stmt = $pdo->prepare($sql);
$result = $stmt->execute();

$client_list = [];
while ($client = $stmt->fetch(PDO::FETCH_ASSOC)) {
$client_list[] = $client;
}

foreach($client_list as $client_list_val){
     $client_list_val['id'];
     $client_list_val['client_name'];
}
$stmt = null;
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

          <h1>案件新規登録</h1>
            <form action="" method="post">
                <table class="table_01">
                     <tr>
                        <th>ステータス<span style="color:red;">*</span>：</th>
                        <td>作業状況
                        <select name="p_work_status">
                        <option value="waiting">未着手</option>
                        <option value="working">進行中</option>
                        <option value="done">完了</option>
                        <option value="canceled">中止</option>
                        </select>
                        　請求状況
                        <select name="p_billing_status">
                        <option value="unbilled">未請求</option>
                        <option value="billed">請求済</option>
                        <option value="paid">入金確認済</option>
                        </select>
                        </td>
                    </tr>
                    <tr>
                        <th>案件種別<span style="color:red;">*</span>：</th>
                        <td>
                          <label><input type="radio" name="p_type" value="1">新規　</label>
                          <label><input type="radio" name="p_type" value="2">保守</label>
                        </td>
                    </tr>
                    <tr>
                        <th>案件名<span style="color:red;">*</span>：</th>
                        <td><input type="text" name="p_name" size="40"></td>
                    </tr>
                    <tr>
                        <th>開始日：</th>
                        <td><input type="date" name="p_start_date"></td>
                    </tr>
                    <tr>
                        <th>完了日：</th>
                        <td><input type="date" name="p_end_date"></td>
                    </tr>
                    <tr>
                        <th>請求日：</th>
                        <td><input type="date" name="p_billing_date"></td>
                    </tr>
                    <tr>
                        <th>金額：</th>
                        <td><input type="number" name="p_amount"> 円</td>
                    </tr>
                    <tr>
                        <th>クライアント<span style="color:red;">*</span>：</th>
                        <td>
                        <select name="p_client" cols="40">
                        <?php foreach($client_list as $client_list_val){ ?>
                        <option value='<?= $client_list_val['id']?>'>
                        <?= $client_list_val['client_name'] ?></option>
                        <?php } ?>
                        </select>
                        </td>
                    </tr>
                    <tr>
                        <th>備考：</th>
                        <td><textarea name="p_remarks" rows="6" cols="40"></textarea></td>
                    </tr>
                </table>
                <input type="submit" class="submit_btn" name="datapost" value="入力内容確認">
            </form>

        </div>
      </div>
      <?php include('../views/menu.inc.php'); ?>
      <?php include('../views/footer.inc.php'); ?>
  </body>
</html>
