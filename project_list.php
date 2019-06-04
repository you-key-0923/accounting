<?php

  include('db_connect.php');
  include('config.php');

  //一括請求済処理
  if(!empty($_POST['billed'])){
    header('Location: processing/billing.php');
    }
    

  

  // 検索パラメタの取得
  // (第一種)ホワイトリストの準備
  
  $search_list = array (
    'search_type',
    'search_name',
    'search_work_status',
    'search_billing_status',
    'search_client',
    'search_remarks'
  );
  

  // データの取得
  $search = [];
  
  foreach($search_list as $p) {
      if (isset($_GET[$p])&&($_GET[$p] !== '') ) {
          $search[$p] = $_GET[$p];
      }
  }

  //SQLの作成
  $sql = 'SELECT
    p.id
  ,p.project_type
  ,p.project_name
  ,p.start_date
  ,p.end_date
  ,p.billing_date
  ,p.amount
  ,c.client_name
  ,p.work_status
  ,p.billing_status
  ,p.remarks

  FROM
  projects AS p

  LEFT JOIN
  clients AS c
  ON c.id = p.client_id';

  // 「検索条件がある」場合の検索条件の付与
  $bind_array = [];
  if (!empty($search)) {
    $where_list = [];
  }else{
  '';
  }

  //検索条件
  if(!empty($search['search_work_status'])){
    if($search['search_work_status'] == 'except_for_canceled'){
      $where_list[] = 'p.work_status != "canceled"';     
    }else{
      $where_list[] = 'p.work_status = :work_status';
      }
    $bind_array[':work_status'] = $search['search_work_status'];
  }
  if(!empty($search['search_billing_status'])){
    if($search['search_billing_status'] == 'except_for_paid'){
      $where_list[] = 'p.billing_status != "paid"';     
    }else{
      $where_list[] = 'p.billing_status = :billing_status';
      }
    $bind_array[':billing_status'] = $search['search_billing_status'];
  }
  if(!empty($search['search_type'])){
    $where_list[] = 'p.project_type = :type';
    $bind_array[':type'] = $search['search_type'];
  }
  if(!empty($search['search_name'])){
    $where_list[] = 'p.project_name = :name';
    $bind_array[':name'] = $search['search_name'];
  }
  if(!empty($search['search_client'])){
    $where_list[] = 'c.client_name like :client';
    $bind_array[':client'] = '%' . $search['search_client'] . '%';
  }
  if(!empty($search['search_remarks'])){
    $where_list[] = 'p.remarks like :remarks';
    $bind_array[':remarks'] = '%' . $search['search_remarks'] . '%';
  }
//日付周りの検索が側だけ。作成するときここに足す！！！

  // WHERE句を合成してSQL文につなげる
  if(!empty($where_list)){
    $sql = $sql . ' WHERE ' . implode(' AND ', $where_list).' ORDER BY p.id';
  }else{
    $sql;
    //$sql = $sql .' WHERE p.work_status != "canceled" AND p.billing_status != "paid" ORDER BY p.id'; //デフォルトの表示条件
  }

  //プリペアドステートメントを作る
  $stmt = $pdo->prepare($sql);

  // 値のバインド
  if (!empty($bind_array)) {
    foreach($bind_array as $k => $v) {
        $stmt->bindValue($k, $v); 
    }
  }

  //SQL文を実行する
  $stmt -> execute();

  $lists = [];
  while ($project = $stmt->fetch(PDO::FETCH_ASSOC)) {
  $lists[] = $project;
  }

  /*----------------------------
      検索結果の合計額の取得
  -----------------------------*/
  $lists_amount = array_column( $lists, 'amount' );
  $amount_sum = array_sum($lists_amount);


  //変数をクリアにする
  $stmt = null;
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

        <!-- //***** 検索フォーム *****// -->
        <form method="get">
        <div class="well">
              <div class="form-group">
                <label for="InputWorkStatus">作業状況</label>
                <select name="search_work_status" class="form-control" id="InputWorkStatus">
                    <option value="" <?php echo empty($_GET['search_work_status']) ? 'selected' : '' ?>>選択しない</option>
                    <option value="waiting" <?php echo isset($_GET['search_work_status']) && $_GET['search_work_status'] == 'waiting' ? 'selected' : '' ?>>未着手</option>
                    <option value="working" <?php echo isset($_GET['search_work_status']) && $_GET['search_work_status'] == 'working' ? 'selected' : '' ?>>進行中</option>
                    <option value="done" <?php echo isset($_GET['search_work_status']) && $_GET['search_work_status'] == 'done' ? 'selected' : '' ?>>完了</option>
                    <option value="canceled" <?php echo isset($_GET['search_work_status']) && $_GET['search_work_status'] == 'canceled' ? 'selected' : '' ?>>中止</option>
                    <option value="except_for_canceled" <?php echo isset($_GET['search_work_status']) && $_GET['search_work_status'] == 'except_for_canceled' ? 'selected' : '' ?>>中止以外</option>
                </select>
            </div>
             <div class="form-group">
                <label for="InputBillingStatus">請求状況</label>
                <select name="search_billing_status" class="form-control" id="InputBillingStatus">
                    <option value="" <?php echo empty($_GET['search_billing_status']) ? 'selected' : '' ?>>選択しない</option>
                    <option value="unbilled" <?php echo isset($_GET['search_billing_status']) && $_GET['search_billing_status'] == 'unbilled' ? 'selected' : '' ?>>未請求</option>
                    <option value="billed" <?php echo isset($_GET['search_billing_status']) && $_GET['search_billing_status'] == 'billed' ? 'selected' : '' ?>>請求済</option>
                    <option value="paid" <?php echo isset($_GET['search_billing_status']) && $_GET['search_billing_status'] == 'paid' ? 'selected' : '' ?>>入金確認済</option>
                    <option value="except_for_paid" <?php echo isset($_GET['search_billing_status']) && $_GET['search_billing_status'] == 'except_for_paid' ? 'selected' : '' ?>>入金確認済以外</option>
                </select>
            </div>
            <div class="form-group">
                <label for="InputType">案件種別</label>
                <select name="search_type" class="form-control" id="InputType">
                    <option value="" <?php echo empty($_GET['search_type']) ? 'selected' : '' ?>>選択しない</option>
                    <option value="1" <?php echo isset($_GET['search_type']) && $_GET['search_type'] == '1' ? 'selected' : '' ?>>新規案件</option>
                    <option value="2" <?php echo isset($_GET['search_type']) && $_GET['search_type'] == '2' ? 'selected' : '' ?>>保守</option>
                </select>
            </div>
            <div class="form-group">
                <label for="InputProject">案件名</label>
                <input name="search_name" class="form-control" id="InputProject" value="<?php echo isset($_GET['search_name']) ? h($_GET['search_name']) : '' ?>">
            </div>
            <div class="form-group">
                <label for="InputClient">請求先</label>
                <input name="search_client" class="form-control" id="InputClient" value="<?php echo isset($_GET['search_client']) ? h($_GET['search_client']) : '' ?>">
            </div>
            <div class="form-group">
                <label for="InputRemarks">備考</label>
                <input name="search_remarks" class="form-control" id="InputRemarks" value="<?php echo isset($_GET['search_remarks']) ? h($_GET['search_remarks']) : '' ?>">
            </div>
            <div class="form-group">
                <label for="InputStartDate">開始日</label>
                <input type="date" name="search_start_date_from" class="form-control" id="InputStartDate" value="<?php echo isset($_GET['search_start_date_from']) ? h($_GET['search_start_date_from']) : '' ?>">
                ～
                <input type="date" name="search_start_date_to" class="form-control" id="InputBillingDate" value="<?php echo isset($_GET['search_start_date_to']) ? h($_GET['search_start_date_to']) : '' ?>">
            </div>
            <div class="form-group">
                <label for="InputEndDate">完了日</label>
                <input type="date" name="search_end_date_from" class="form-control" id="InputEndDate" value="<?php echo isset($_GET['search_end_date_from']) ? h($_GET['search_end_date_from']) : '' ?>">
                ～
                <input type="date" name="search_end_date_to" class="form-control" id="InputBillingDate" value="<?php echo isset($_GET['search_end_date_to']) ? h($_GET['search_end_date_to']) : '' ?>">
            </div>
            <div class="form-group">
                <label for="InputBillingDate">請求日</label>
                <input type="date" name="search_billing_date_from" class="form-control" id="InputBillingDate" value="<?php echo isset($_GET['search_billing_date_from']) ? h($_GET['search_billing_date_from']) : '' ?>">
                ～
                <input type="date" name="search_billing_date_to" class="form-control" id="InputBillingDate" value="<?php echo isset($_GET['search_billing_date_to']) ? h($_GET['search_billing_date_to']) : '' ?>">
            </div>
         <button type="submit" class="btn btn-default" name="search">検索</button>
        </div>

	</form>

      <p>表示案件の合計額は「<?= number_format($amount_sum) ?> 円」です。</p>
      <form method="post" action="processing/billing.php">
      <input type="submit" name="billed" value="一括請求済処理">


          <!-- /***** 表示テーブル *****// -->
          <div class="example">
          <table class="list">
            <tr>
              <th></th>
              <th>No.</th>
              <th>作業状況</th>
              <th>請求状況</th>
              <th>種別</th>
              <th>案件名</th>
              <th>開始日</th>
              <th>完了日</th>
              <th>請求日</th>
              <th>金額</th>
              <th>請求先</th>
              <th>編集</th>
              <th>処理</th>

            </tr>

            <?php

            foreach ($lists as $list) { ?>
              <tr>
                <td><input type="checkbox" name="check[]" value="<?= $list['id']; ?>"></td>
                <td><?= $list['id']; ?></td>
                <td><?= $status_text[$list['work_status']]; ?></td>
                <td><?= $status_text[$list['billing_status']]; ?></td>
                <td><?= $project_type_text[$list['project_type']] ?></td>
                <td><a href='project/view.php?id=<?= $list['id'] ?>'><?= $list['project_name']; ?></a></td>
                <td><?php echo $list['start_date'] !== '0000-00-00'? $list['start_date'] : '-' ?></td>
                <td><?php echo $list['end_date'] !== '0000-00-00'? $list['end_date'] : '-' ?></td>
                <td><?php echo $list['billing_date'] !== '0000-00-00'? $list['billing_date'] : '-' ?></td>
                <td>￥<?= number_format($list['amount']); ?></td>
                <td><?= $list['client_name']; ?></td>
                <td><a href='project/edit.php?id=<?= $list['id'] ?>'><img src="<?php echo PROJECT_PATH?>/image/edit_icon.png" alt="edit" height="15"></a></td>
                <td><a href='processing/index.php?id=<?= $list['id'] ?>' onClick="window.open('processing/index.php?id=<?= $list['id'] ?>', '別ウィンドウ', 'top=250,left=300,width=500,height=400'); return false;"><img src="<?php echo PROJECT_PATH?>/image/billing.jpg" alt="edit" height="15"></a></td>
              </tr>
            <?PHP  } ?>
          </table>
            </form>
          </div>

        </div>
      </div>
      <?php include('views/menu.inc.php'); ?>
      <?php include('views/footer.inc.php'); ?>


  </body>
</html>
