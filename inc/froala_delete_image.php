<?php
include "../_inc/_common.php";
header("Content-Type: application/json;charset=utf-8");

$slen = strlen($_cfg["url"]);
if(substr($_SERVER['HTTP_REFERER'], 0, $slen) != $_cfg["url"]){
	$response = new StdClass;
	$response->error = "잘못된 접근입니다.";
	echo json_encode($response);
	exit;
}

// 파일검사
if($_POST["src"]){
	@unlink($_cfg['web_home'].$_POST["src"]);
}else{

	$response = new StdClass;
	$response->error = "파일을 업로드해 주세요.";
	echo json_encode($response);
	exit;
}

?>