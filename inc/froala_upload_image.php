<?php
include "../_inc/_common.php";
header("Content-Type: application/json;charset=utf-8");

$slen = strlen($_cfg["url"]);
if(substr($_SERVER['HTTP_REFERER'], 0, $slen) != $_cfg['url']){
	$response = new StdClass;
	$response->error = "잘못된 접근입니다.(illegal access)".$_cfg['url']."";
	echo json_encode($response);
	exit;
}

// 파일검사
if($_FILES["editor_img"]["name"]){

	$timg = @getimagesize($_FILES["editor_img"]["tmp_name"]);


	$mo_type = "";
	$ext = "";
	if($timg[2] == 1){
		$ext = "gif";
		$mo_type = "gif";
	}else if($timg[2] == 2){
		$ext = "jpg";
		$mo_type = "jpg";
	}else if($timg[2] == 3){
		$ext = "png";
		$mo_type = "png";
	}else{
		$response = new StdClass;
		$response->error = "jpg, gif, png 파일만 업로드 가능합니다.";
		echo stripslashes(json_encode($response));
		exit;
	}


	$src = $_FILES["editor_img"]["tmp_name"];
	//$ext = strtolower(get_file_ext($_FILES[editor_img][name]));
	$tgt_name = md5(uniqid(rand(), TRUE)).".".$ext;
	$org_name = $_FILES['editor_img']['name'];
	$tgt = $_cfg['web_home'].$_cfg['data_dir']."/editor/".$tgt_name;

	@move_uploaded_file($src, $tgt);
	@chmod($tgt, 0666);


	$response = new StdClass;
	$response->link = $_cfg['data_dir']."/editor/".$tgt_name;
	echo stripslashes(json_encode($response));
	exit;
}else{

	$response = new StdClass;
	$response->error = "파일을 업로드해 주세요.";
	echo json_encode($response);
	exit;
}

?>