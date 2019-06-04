<?php
//DB接続情報をまとめるやつを作成中

function db_connect(){
  //DBの接続情報
$host = "localhost";
$user = "root";
$password = "1234";
$name = "accounting";
//MySQLのDSN文字列
$dsn = "mysql:host={$host}; dbname={$name}; charset=utf8";

try{
  $pdo = new PDO($dsn,$user,$password);
}catch(PDOException $e){
  echo 'DB接続エラー:' . $e->getMessage();
  exit;
}
return $pdo;
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>請求管理ツール</title>
    <link href="style.css" rel="stylesheet" type="text/css" />
    <style>
      table.from_table {
        margin-top: 5px;
        border: 1px solid #ccc;
        width: 100%;
      }

      table.from_table tr th {
        background: #dcdcdc;
        width: 5%;
        white-space: nowrap;
        font-size: 10px;
        font-weight: 700;
        text-align: center;
        padding: 3px
     }

     table.from_table tr td {
        width: 20%;
        vertical-align: top;
        padding: 3px
     }
    </style>
</head>

<body>
<table class="from_table">
<tr>
  <th>1</th>
  <td>1-1
  <th>2</th>
  <td>2-1</td>

<th>3</th>
<td>3-1</td>
</tr>
<tr>
<th>4</th>
<td>4-1</td>
<th>5</th>
<td>5-1</td>
<th>6</th>
<td>6-1</td>
</tr>
</table>



<BR><BR><BR>
<hr>
<span class="status-waiting">未着手</span>
<span class="status-working">進行中</span>
<span class="status-done">完了</span>
<span class="status-canceled">中止</span>
<span class="status-unbilled">未請求</span>
<span class="status-billed">請求済</span>
<span class="status-paid">入金確認済</span>


<a href="another.html" onclick="window.open('another.html', '別ウィンドウ', 'width=400,height=400'); return false;">クリックすると別ウインドウが開きます</a>
<a href="another.html" onclick="window.open('another.html', '別ウィンドウ', 'width=400,height=400'); return false;">クリックすると別ウインドウが開きます</a>

<td><a href='processing/index.php?id=<?= $list['id'] ?>' onClick="window.open('processing/index.php?id=<?= $list['id'] ?>', '別ウィンドウ', 'width=400,height=400'); return false;"><img src="<?php echo PROJECT_PATH?>/image/billing.jpg" alt="edit" height="15"></a></td>

<form action="" method="post" onSubmit=”return checkSubmit()”>
<input type="submit" onSubmit="confirm('送信しますか？')">
</form>




<button id="openModal" type="submit">Open modal</button>

<!-- モーダルエリアここから -->
<section id="modalArea" class="modalArea">
  <div id="modalBg" class="modalBg"></div>
  <div class="modalWrapper">
    <div class="modalContents">
      <h1>Here are modal contents!</h1>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
    </div>
    <div id="closeModal" class="closeModal">
      ×
    </div>
  </div>
</section>






<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
      <script>
        $(function () {
        $('#openModal').click(function(){
            $('#modalArea').fadeIn();
        });
        $('#closeModal , #modalBg').click(function(){
          $('#modalArea').fadeOut();
        });
      });
      </script>


<script>
function checkSubmit(){
if(window.confirm(‘送信しますか？’)){
return true;
}
else{
return false;
}
}
</script>
</body>
</html>





