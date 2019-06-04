<?php 

include('../db_connect.php');
include('../config.php');

//REQUESTがあるかどうか判断！
/*
if(isset($_REQUEST['id'])) {
$id = $_REQUEST['id'];
}else{
header('Location: ../project_list.php');
}
*/

/*----------------------------
    登録データを抽出
-----------------------------*/
$id = $_REQUEST['id'];

///SQL文を作る
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

FROM projects AS p

LEFT JOIN clients AS c
ON c.id = p.client_id

WHERE p.id = :id';

//プリペアドステートメントを作る
$stmt = $pdo->prepare($sql);

//バインド
$stmt ->bindValue(':id', $id, PDO::PARAM_INT);

//SQL文を実行する
$stmt -> execute();

$project = $stmt->fetch(PDO::FETCH_ASSOC);

//**** SQL実行して、データ引いてこなかったら（REQUESTしたidの数字がなかったら）
/*
if(empty($client)){
header('Location: index.php');
}
*/


 /*----------------------------
      入力内容をUPDATE
  -----------------------------*/

if(!empty($_POST)){
$update = [];
$update = $_POST;
$update_id = $_POST['id'];
}

//UPDATE用のSQLの作成
$sql = 'UPDATE projects SET 
billing_date=:billing_date
,billing_status=:billing_status
,updated_at=now()

WHERE id=:id';

$stmt = $pdo->prepare($sql);

//請求済みにする方
if(!empty($_POST['billing_update'])){
    // 値のバインド
    $stmt->bindValue(':id', $update_id, PDO::PARAM_INT);
    $stmt->bindValue(':billing_date', $update['billing_date'], PDO::PARAM_STR);
    $stmt->bindValue(':billing_status', 'billed', PDO::PARAM_STR);

    // SQLの実行
    $stmt ->execute();

    header('Location: index.php?id='.$id);
}

//入金済みにする方
if(!empty($_POST['paid_update'])){
    // 値のバインド
    $stmt->bindValue(':id', $update_id, PDO::PARAM_INT);
    $stmt->bindValue(':billing_date', $project['billing_date'], PDO::PARAM_STR);
    $stmt->bindValue(':billing_status', 'paid', PDO::PARAM_STR);

    // SQLの実行
    $stmt ->execute();

    header('Location: index.php?id='.$id);
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
    <link href="../style.css" rel="stylesheet" type="text/css" />
    <style>
    body {
    background-color:#EFEFEF;
    }
    </style>
    <script>
    function sample() {
    window.close();
    }
    </script>
</head>

<body>
    <div class="box01">
    <span class="status-<?= $project['work_status']; ?>"><?= $status_text[$project['work_status']]; ?></span>
    <span class="status-<?= $project['billing_status']; ?>"><?= $status_text[$project['billing_status']]; ?></span>
    </div>

    <div class="box02">
    <p>No.<?= $project['id']; ?>【<?= $project_type_text[$project['project_type']]; ?>】</p>
    <h1><?= $project['project_name']; ?></h1>
    <p><?= number_format($project['amount']); ?> 円</p>
    </div>
    
    <div class="box28">
        <span class="box-title">請求済みにする</span>
        <form action="" method="post">
            <input type="hidden" name="id" value="<?= $project['id']; ?>">
            請求日  <input type="date" name="billing_date">
            <input type="submit" name="billing_update" value="更新">
        </form>
    </div>
    <div class="box28">
        <span class="box-title">入金確認済みにする</span>
        <form action="" method="post">
            <input type="hidden" name="id" value="<?= $project['id']; ?>">
            <input type="submit" name="paid_update" value="更新">
        </form>
    </div>
    <center><a onclick="sample()">×閉じる×</a></center>
</body>
</html>
