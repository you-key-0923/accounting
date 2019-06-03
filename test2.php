<?php
include('test.php');

$hoge = db_connect();

$sql = 'SELECT id,client_name FROM clients';
$stmt = $hoge->prepare($sql);
$result = $stmt->execute();

$client_list = [];
while ($client = $stmt->fetch(PDO::FETCH_ASSOC)) {
$client_list[] = $client;
}


var_dump($client_list);