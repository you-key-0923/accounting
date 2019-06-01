<?php
$hoge = 'てすと';
$params['name'] = '';
/*
$where = [];
	if(!empty($params['name'])){
		$where[] = "name like '%{$params['name']}%'";
    }
    */
?>

<?php echo isset($params['name']) && $params['name'] == 'プロジェクト' ? 'selected' : 'あ' ?>

$変数が空じゃない＆$変数が'テキスト'と同じなら、'selected'そうじゃないなら'あ'