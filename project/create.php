<?php 
include_once('../db_connect.php');
include('../config.php');

session_start();
$_SESSION['join'] = $_POST;

if(!empty($_POST['datapost'])){
header('Location: confirm.php');
}


/*----------------------------
    取引先の情報を抽出
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
                        <th>案件種別【必須】</th>
                        <td>
                          <label><input type="radio" name="p_type" value="1">新規</label>
                          <label><input type="radio" name="p_type" value="2">保守</label>
                        </td>
                    </tr>
                    <tr>
                        <th>案件名【必須】</th>
                        <td><input type="text" name="p_name"></td>
                    </tr>
                    <tr>
                        <th>開始日</th>
                        <td><input type="date" name="p_start_date"></td>
                    </tr>
                    <tr>
                        <th>完了日</th>
                        <td><input type="date" name="p_end_date"></td>
                    </tr>
                    <tr>
                        <th>請求日</th>
                        <td><input type="date" name="p_billing_date"></td>
                    </tr>
                    <tr>
                        <th>金額</th>
                        <td><input type="number" name="p_amount"></td>
                    </tr>
                    <tr>
                        <th>請求先</th>
                        <td>
                        <select name="p_client">
                        <?php foreach($client_list as $client_list_val){ ?>
                        <option value='<?= $client_list_val['id']?>'>
                        <?= $client_list_val['client_name'] ?></option>
                        <?php } ?>
                        </select>
                        </td>
                    </tr>
                    <tr colspan="2">
                        <td><input type="submit" class="submit_btn" name="datapost" value="入力内容確認"></td>
                    </tr>
                </table>
            </form>

        </div>
      </div>
      <?php include('../views/menu.inc.php'); ?>
      <?php include('../views/footer.inc.php'); ?>
  </body>
</html>
