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



