<?php

    include('db_connect.php');
    include('config.php');


    // 検索パラメタの取得
    // (第一種)ホワイトリストの準備
    $search_list = array (
      'search_type',
      'search_name',
      'search_status',
      'search_client',
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
    ,s.status_text
    ,c.client_name
    ,p.status

    FROM
    projects AS p

    LEFT JOIN
    status AS s
    ON s.status_en = p.status
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
    if(!empty($search['search_type'])){
      $where_list[] = 'p.project_type = :type';
      $bind_array[':type'] = $search['search_type'];
    }
    if(!empty($search['search_name'])){
      $where_list[] = 'p.project_name = :name';
      $bind_array[':name'] = $search['search_name'];
    }
    if(!empty($search['search_status'])){
      $where_list[] = 'p.status = :status';
      $bind_array[':status'] = $search['search_status'];
    }
    if(!empty($search['search_client'])){
      $where_list[] = 'c.client_name like :client';
      $bind_array[':client'] = '%' . $search['search_client'] . '%';
    }

    // WHERE句を合成してSQL文につなげる
    if(!empty($where_list)){
      $sql = $sql . ' WHERE ' . implode(' AND ', $where_list).' ORDER BY p.id';
    }else{
      $sql = $sql .' ORDER BY p.id';
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
                <label for="InputType">案件種別</label>
                <select name="search_type" class="form-control" id="InputType">
                    <option value="0" <?php echo empty($_GET['search_type']) ? 'selected' : '' ?>>選択しない</option>
                    <option value="1" <?php echo isset($_GET['search_type']) && $_GET['search_type'] == '1' ? 'selected' : '' ?>>新規案件</option>
                    <option value="2" <?php echo isset($_GET['search_type']) && $_GET['search_type'] == '2' ? 'selected' : '' ?>>保守</option>
                </select>
            </div>
            <div class="form-group">
                <label for="InputProject">案件名</label>
                <input name="search_name" class="form-control" id="InputProject" value="<?php echo isset($_GET['search_name']) ? htmlspecialchars($_GET['search_name']) : '' ?>">
            </div>
            <div class="form-group">
                <label for="InputStatus">状況</label>
                <select name="search_status" class="form-control" id="InputType">
                    <option value="0" <?php echo empty($_GET['search_status']) ? 'selected' : '' ?>>選択しない</option>
                    <option value="waiting" <?php echo isset($_GET['search_status']) && $_GET['search_status'] == 'waiting' ? 'selected' : '' ?>>未着手</option>
                    <option value="working" <?php echo isset($_GET['search_status']) && $_GET['search_status'] == 'working' ? 'selected' : '' ?>>進行中</option>
                    <option value="done" <?php echo isset($_GET['search_status']) && $_GET['search_status'] == 'done' ? 'selected' : '' ?>>完了</option>
                    <option value="billed" <?php echo isset($_GET['search_status']) && $_GET['search_status'] == 'billed' ? 'selected' : '' ?>>請求済</option>
                    <option value="paid" <?php echo isset($_GET['search_status']) && $_GET['search_status'] == 'paid' ? 'selected' : '' ?>>入金確認済</option>
                    <option value="Canceled" <?php echo isset($_GET['search_status']) && $_GET['search_status'] == 'Canceled' ? 'selected' : '' ?>>中止</option>
                </select>
            </div>
            <div class="form-group">
                <label for="InputClient">請求先</label>
                <input name="search_client" class="form-control" id="InputClient" value="<?php echo isset($_GET['search_client']) ? htmlspecialchars($_GET['search_client']) : '' ?>">
            </div>
        <button type="submit" class="btn btn-default" name="search">検索</button>
        </div>

	</form>



          <!-- <a href="project/create.php" class="btn-open">新規登録</a> -->

          <!-- /***** 表示テーブル *****// -->
          <div class="example">
          <table class="list">
            <tr>
              <th>No.</th>
              <th>作業状況</th>
              <th>種別</th>
              <th>案件名</th>
              <th>開始日</th>
              <th>完了日</th>
              <th>請求日</th>
              <th>金額</th>
              <th>請求先</th>
              <th>請求状況</th>
              <th>請求処理</th>
            </tr>

            <?php

            //project_typeをテキストに置換
            $project_type_text = [
            "1" => "新規",
            "2" => "保守",
            ];

            foreach ($lists as $list) { ?>
              <tr>
                <td><a href='project/view.php?id=<?= $list['id'] ?>'><?= $list['id']; ?></a></td>
                <td><?= $list['status_text']; ?></td>
                <td><?= $project_type_text[$list['project_type']] ?></td>
                <td><?= $list['project_name']; ?></td>
                <td><?= $list['start_date']; ?></td>
                <td><?= $list['end_date']; ?></td>
                <td><?= $list['billing_date']; ?></td>
                <td><?= $list['amount']; ?></td>
                <td><?= $list['client_name']; ?></td>
                <td>未入金</td>
                <td><img src="image/billing.jpg" alt="pig"></td>
                <td>
              </tr>
            <?PHP  } ?>
          </table>
          </div>

        </div>
      </div>
      <?php include('views/menu.inc.php'); ?>
      <?php include('views/footer.inc.php'); ?>
  </body>
</html>
