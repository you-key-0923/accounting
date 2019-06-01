<?php
  include_once('../db_connect.php');
  include('../config.php');


  //***** 登録情報の読み出し *****//

  if(isset($_REQUEST['id'])) {
        $id = $_REQUEST['id'];
    }

  //SQL文を作る
  $sql = 'SELECT
   p.id
  ,p.project_type
  ,p.project_name
  ,p.start_date
  ,p.end_date
  ,p.billing_date
  ,p.amount
  ,p.client_id
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

  WHERE p.id = :id';

  //プリペアドステートメントを作る
  $stmt = $pdo->prepare($sql);

  //バインド
  $stmt ->bindValue(':id', $id, PDO::PARAM_INT);

  //SQL文を実行する
  $stmt -> execute();

  $project = $stmt->fetch(PDO::FETCH_ASSOC);

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


  //---------------------------------
  //***** 更新情報をUPDATEする *****/
  //---------------------------------

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
  $sql = 'UPDATE projects SET project_type=:type, project_name=:name, start_date=:start_date, end_date=:end_date, billing_date=:billing_date, amount=:amount, client_id=:client_id, updated_at=now() WHERE id=:id';

  $stmt = $pdo->prepare($sql);

  // 値のバインド
  $stmt->bindValue(':id', $update_id, PDO::PARAM_INT);
  $stmt->bindValue(':type', $update['type'], PDO::PARAM_INT);
  $stmt->bindValue(':name', $update['name'], PDO::PARAM_STR);
  $stmt->bindValue(':start_date', $update['start_date'], PDO::PARAM_STR);
  $stmt->bindValue(':end_date', $update['end_date'], PDO::PARAM_STR);
  $stmt->bindValue(':billing_date', $update['billing_date'], PDO::PARAM_STR);
  $stmt->bindValue(':amount', $update['amount'], PDO::PARAM_INT);
  $stmt->bindValue(':client_id', $update['client_id'], PDO::PARAM_INT);

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
          <input type="hidden" name="id" value="<?= $project['id']; ?>">
          <table class="table_01">
          <tr><th>案件種別：</th><td>
          <label><input type="radio" name="type" value="1" <?php echo $project['project_type'] === '1' ? 'checked="checked"' : '' ?>>新規</label>
          <label><input type="radio" name="type" value="2" <?php echo $project['project_type'] === '2' ? 'checked="checked"' : '' ?>>保守</label>  
          </td></tr>
          <tr><th>案件名：</th><td><input type="text" name="name" value="<?= htmlspecialchars($project['project_name'],ENT_QUOTES); ?>"></td></tr>
          <tr><th>開始日：</th><td><input type="date" name="start_date" value="<?= htmlspecialchars($project['start_date'],ENT_QUOTES); ?>"></td></tr>
          <tr><th>完了日：</th><td><input type="date" name="end_date" value="<?= htmlspecialchars($project['end_date'],ENT_QUOTES); ?>"></td></tr>
          <tr><th>請求日：</th><td><input type="date" name="billing_date" value="<?= htmlspecialchars($project['billing_date'],ENT_QUOTES); ?>"></td></tr>
          <tr><th>金額：</th><td><input type="number" name="amount" value="<?= htmlspecialchars($project['project_name'],ENT_QUOTES); ?>"></td></tr>
          <tr><th>請求先：</th><td><select name="client_id">
          <?php foreach($client_list as $client_list_val){ ?>
          <option value="
          <?php if($project['client_id'] === $client_list_val['id']): 
            echo $client_list_val['id'] ?>" selected >
          <?php else:
            echo $client_list_val['id'] ?>">
          <?php endif; ?>
          
          <?= $client_list_val['client_name'] ?></option>
          <?php } ?>
          </select></td></tr>
          </table>

          <input type="submit" class="submit_btn" value="更新">
         </form>
        </div>
      </div>
      <?php include('../views/menu.inc.php'); ?>
      <?php include('../views/footer.inc.php'); ?>
  </body>
</html>