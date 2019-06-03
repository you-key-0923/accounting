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
  $sql = 'SELECT
   p.id
  ,p.project_type
  ,p.project_name
  ,p.start_date
  ,p.end_date
  ,p.billing_date
  ,p.amount
  ,p.work_status
  ,p.billing_status
  ,p.remarks
  ,p.created_at
  ,p.updated_at
  ,p.client_id
  ,c.client_name

  FROM
  projects AS p

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
  var_dump($project['remarks']);

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
  $sql = 'UPDATE projects SET 
  project_type=:type
  ,project_name=:name
  ,start_date=:start_date
  ,end_date=:end_date
  ,billing_date=:billing_date
  ,amount=:amount
  ,work_status=:work_status
  ,billing_status=:billing_status
  ,remarks=:remarks
  ,client_id=:client_id
  ,updated_at=now()

  WHERE id=:id';

  $stmt = $pdo->prepare($sql);

  // 値のバインド
  $stmt->bindValue(':id', $update_id, PDO::PARAM_INT);
  $stmt->bindValue(':type', $update['type'], PDO::PARAM_INT);
  $stmt->bindValue(':name', $update['name'], PDO::PARAM_STR);
  $stmt->bindValue(':start_date', $update['start_date'], PDO::PARAM_STR);
  $stmt->bindValue(':end_date', $update['end_date'], PDO::PARAM_STR);
  $stmt->bindValue(':billing_date', $update['billing_date'], PDO::PARAM_STR);
  $stmt->bindValue(':amount', $update['amount'], PDO::PARAM_INT);
  $stmt->bindValue(':work_status', $update['work_status'], PDO::PARAM_STR);
  $stmt->bindValue(':billing_status', $update['billing_status'], PDO::PARAM_STR);
  $stmt->bindValue(':remarks', $update['remarks'], PDO::PARAM_STR);
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

        <h1>案件編集</h1>

        <form action="" method="post">
        <input type="hidden" name="id" value="<?= $project['id']; ?>">
        <table class="table_01">
        <tr>
          <th>ステータス<span style="color:red;">*</span>：</th>
          <td>作業状況     
          <select name="work_status">
          <option value="waiting"<?php echo $project["work_status"] == 'waiting' ? ' selected' : '' ?>>未着手</option>
          <option value="working"<?php echo $project["work_status"] == 'working' ? ' selected' : '' ?>>進行中</option>
          <option value="done"<?php echo $project["work_status"] == 'done' ? ' selected' : '' ?>>完了</option>
          <option value="canceled"<?php echo $project["work_status"] == 'canceled' ? ' selected' : '' ?>>中止</option>
          </select>
          　請求状況
          <select name="billing_status">
          <option value="unbilled"<?php echo $project["billing_status"] == 'unbilled' ? ' selected' : '' ?>>未請求</option>
          <option value="billed"<?php echo $project["billing_status"] == 'billed' ? ' selected' : '' ?>>請求済</option>
          <option value="paid"<?php echo $project["billing_status"] == 'paid' ? ' selected' : '' ?>>入金確認済</option>
          </select>
          </td>
        </tr>
        <tr>
          <th>案件種別<span style="color:red;">*</span>：</th>
          <td><label><input type="radio" name="type" value="1" <?php echo $project['project_type'] === '1' ? 'checked="checked"' : '' ?>>新規</label>
          <label><input type="radio" name="type" value="2" <?php echo $project['project_type'] === '2' ? 'checked="checked"' : '' ?>>保守</label>  </td>
        </tr>
        <tr>
          <th>案件名<span style="color:red;">*</span>：</th>
          <td><input type="text" name="name" value="<?= h($project['project_name']); ?>"></td>
        </tr>
        <tr>
          <th>開始日：</th>
          <td><input type="date" name="start_date" value="<?= h($project['start_date']); ?>"></td>
        </tr>
        <tr>
          <th>完了日：</th>
          <td><input type="date" name="end_date" value="<?= h($project['end_date']); ?>"></td>
        </tr>
        <tr>
          <th>請求日：</th>
          <td><input type="date" name="billing_date" value="<?= h($project['billing_date']); ?>"></td>
        </tr>
        <tr>
          <th>金額：</th>
          <td><input type="number" name="amount" value="<?= h($project['amount']); ?>"> 円</td>
        </tr>
        <tr>
          <th>請求先：</th>
          <td><select name="client_id">
          <?php foreach($client_list as $client_list_val){ ?>
          <option value="
          <?php if($project['client_id'] === $client_list_val['id']): 
            echo $client_list_val['id'] ?>" selected >
          <?php else:
            echo $client_list_val['id'] ?>">
          <?php endif; ?>
          
          <?= $client_list_val['client_name'] ?></option>
          <?php } ?>
          </select></td>
        </tr>
        <tr>
            <th>備考</th>
            <td><textarea name="remarks" rows="6" cols="40"><?= h($project['remarks']) ?></textarea></td>
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