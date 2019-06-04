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
  </head>

  <body>
  <span class="status-waiting">未着手</span>
<span class="status-working">進行中</span>
<span class="status-done">完了</span>
<span class="status-canceled">中止</span>
<span class="status-unbilled">未請求</span>
<span class="status-billed">請求済</span>
<span class="status-paid">入金確認済</span>


<a href="another.html" onclick="window.open('another.html', '別ウィンドウ', 'width=400,height=400'); return false;">クリックすると別ウインドウが開きます</a>

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





