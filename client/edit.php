<?php
  include_once('../db_connect.php');
  include('../config.php');


/*----------------------------
    登録データ読み出し
-----------------------------*/

  if(isset($_REQUEST['id'])) {
        $id = $_REQUEST['id'];
    }

  //SQL文を作る
  $sql = 'SELECT * FROM clients WHERE id = :id';

  //プリペアドステートメントを作る
  $stmt = $pdo->prepare($sql);

  //バインド
  $stmt ->bindValue(':id', $id, PDO::PARAM_INT);

  //SQL文を実行する
  $stmt -> execute();

  $client = $stmt->fetch(PDO::FETCH_ASSOC);


  /*----------------------------
      入力内容をUPDATE
  -----------------------------*/
  if(!empty($_POST)){

    // 「パラメタの一覧」を把握
    //$params = array('id','type', 'name', 'start_date', 'end_date','billing_date','amount','client');

    //更新データ格納用の変数の用意
    $update = [];
    $update = $_POST;

    // idは別途取得しておく
    $update_id = $_POST['id'];
    //var_dump($update);

    //UPDATE用のSQLの作成
    $sql = 'UPDATE clients SET 
    client_name=:name
    ,zip_code=:zip_code
    ,address=:address
    ,tel1=:tel1
    ,tel2=:tel2
    ,staff=:staff
    ,remarks=:remarks
    ,updated_at=now()
    WHERE id=:id';

    $stmt = $pdo->prepare($sql);

    // 値のバインド
    $stmt->bindValue(':id', $update_id, PDO::PARAM_INT);
    $stmt->bindValue(':name', $update['client_name'], PDO::PARAM_STR);
    $stmt->bindValue(':zip_code', $update['zip_code'], PDO::PARAM_STR);
    $stmt->bindValue(':address', $update['address'], PDO::PARAM_STR);
    $stmt->bindValue(':tel1', $update['tel1'], PDO::PARAM_STR);
    $stmt->bindValue(':tel2', $update['tel2'], PDO::PARAM_STR);
    $stmt->bindValue(':staff', $update['staff'], PDO::PARAM_STR);
    $stmt->bindValue(':remarks', $update['remarks'], PDO::PARAM_STR);

    // SQLの実行
    $stmt ->execute();

    //変数をクリアにする
    $stmt = null;
    $pdo = null;

    header('Location: view.php?id='.$id);
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

        <h1>案件詳細</h1>

        <form action="" method="post">
        <input type="hidden" name="id" value="<?= $client['id']; ?>">
        <table class="table_01">
        <tr>
          <th>No.：</th>
          <td><?= h($client['id']); ?></td>
        </tr>
        <tr>
          <th>クライアント名<span style="color:red;">*</span>：</th>
          <td><input type="text" name="client_name" value="<?= h($client['client_name']); ?>"></td>
        </tr>
        <tr>
          <th>郵便番号：</th>
          <td>〒<input type="text" name="zip_code" value="<?= h($client['zip_code']); ?>"></td>
        </tr>
        <tr>
          <th>住所：</th>
          <td><input type="text" name="address" value="<?= h($client['address']); ?>"></td>
        </tr>
        <tr>
          <th>電話番号１：</th>
          <td><input type="text" name="tel1" value="<?= h($client['tel1']); ?>"></td>
        </tr>
        <tr>
          <th>電話番号２：</th>
          <td><input type="text" name="tel2" value="<?= h($client['tel2']); ?>"></td>
        </tr>
        <th>担当者名：</th>
          <td><input type="text" name="staff" value="<?= h($client['staff']); ?>"></td>
        </tr>
        <tr>
            <th>備考：</th>
            <td><textarea name="remarks" rows="6" cols="40"><?= h($client['remarks']) ?></textarea></td>
        </tr>

        </table>

        <input type="submit" class="submit_btn" value="更新">
        </form>
      </div>
    </div>
    <?php include('../views/menu.inc.php'); ?>
    <?php include('../views/footer.inc.php'); ?>
  </body>
</html>