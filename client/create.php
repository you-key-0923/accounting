<?php 

include_once('../db_connect.php');
include('../config.php');

session_start();
$_SESSION['join'] = $_POST;

if(!empty($_POST['datapost'])){
header('Location: confirm.php');
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

          <h1>クライアント新規登録</h1>
            <form action="" method="post">
                <table class="table_01">
                     <tr>
                        <th>クライアント名<span style="color:red;">*</span>：</th>
                        <td><input type="text" name="client_name"></td>
                    </tr>
                    <tr>
                        <th>郵便番号：</th>
                        <td><input type="text" name="zip_code"></td>
                    </tr>
                    <tr>
                        <th>住所：</th>
                        <td><input type="text" name="address"></td>
                    </tr>
                    <tr>
                        <th>電話番号１：</th>
                        <td><input type="text" name="tel1"></td>
                    </tr>
                    <tr>
                        <th>電話番号２：</th>
                        <td><input type="text" name="tel2"></td>
                    </tr>
                    <tr>
                        <th>担当者名：</th>
                        <td><input type="text" name="staff"></td>
                    </tr>
                    <tr>
                        <th>備考</th>
                        <td><textarea name="remarks" rows="6" cols="40"></textarea></td>
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
