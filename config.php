<?php

define("PROJECT_PATH", "/accounting");

function h($s) {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

// project_typeを置換
$project_type_text = [
    "1" => "新規",
    "2" => "保守",
  ];

// XXX_statusを置換
$status_text = [
    "waiting" => "未着手",
    "working" => "進行中",
    "done" => "完了",
    "canceled" => "中止",
    "unbilled" => "未請求",
    "billed" => "請求済",
    "paid" => "入金確認済"
];

//日付の「0000-00-00」表記を「-」に置換
function show_date($d) {
    echo $d !== '0000-00-00'? htmlspecialchars($d) : '-';
  }



