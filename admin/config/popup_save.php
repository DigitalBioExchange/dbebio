<?php
$menu_code = "110200";
$menu_mode = "w";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

$query = $_POST['query'];

//파일체크
$field_arr = array("pp_file");
foreach($field_arr as $k => $v){
	if($_FILES[$v]['tmp_name']){
		$timg = @getimagesize($_FILES[$v]['tmp_name']);
		if($timg[2] != 1 && $timg[2] != 2 && $timg[2] != 3) alert("jpg, gif, png 파일만 업로드 가능합니다.");
	}
}

$sql_common = "
	pp_title = '".$_POST['pp_title']."',
	pp_left = '".$_POST['pp_left']."',
	pp_top = '".$_POST['pp_top']."',
	pp_agent = '".$_POST['pp_agent']."',
	pp_use = '".$_POST['pp_use']."',
	pp_link = '".$_POST['pp_link']."'
";

if($_POST['mode'] == "insert"){

	$data = sql_fetch("select * from rb_popup where pp_idx = '".$_POST['pp_idx']."'");

	$sql = "insert into rb_popup set
				$sql_common
			";
	$sql_q = sql_query($sql);
	$pp_idx = mysql_insert_id();

	//파일저장
	foreach($field_arr as $k => $v){
		if($_FILES[$v]['tmp_name']){
			$src = $_FILES[$v]['tmp_name'];
			$ext = get_file_ext($_FILES[$v]['name']);
			$tgt_name = md5(uniqid(rand(), TRUE)).".".$ext;
			$org_name = $_FILES[$v]['name'];
			$tgt = $_cfg['web_home'].$_cfg['data_dir']."/files/".$tgt_name;

			@move_uploaded_file($src, $tgt);
			@chmod($tgt, 0666);

			sql_query("update rb_popup set $v = '$tgt_name' where pp_idx = '$pp_idx'");

		}
	}


	alert("팝업이 추가 되었습니다.", "./popup_list.php?$query");
}else if($_POST['mode'] == "update" && $_POST['pp_idx']){

	$data = sql_fetch("select * from rb_popup where pp_idx = '".$_POST['pp_idx']."'");

	$sql = "update rb_popup set
				$sql_common
			where pp_idx = '".$_POST['pp_idx']."'
			";
	$sql_q = sql_query($sql);
	$pp_idx = $_POST['pp_idx'];

	//파일저장
	foreach($field_arr as $k => $v){
		if($_FILES[$v]['tmp_name']){
			$src = $_FILES[$v]['tmp_name'];
			$ext = get_file_ext($_FILES[$v]['name']);
			$tgt_name = md5(uniqid(rand(), TRUE)).".".$ext;
			$org_name = $_FILES[$v]['name'];
			$tgt = $_cfg['web_home'].$_cfg['data_dir']."/files/".$tgt_name;

			@move_uploaded_file($src, $tgt);
			@chmod($tgt, 0666);

			sql_query("update rb_popup set $v = '$tgt_name' where pp_idx = '$pp_idx'");

		}
	}


	alert("팝업이 수정 되었습니다.", "./popup_list.php?$query");
}else if($_GET['mode'] == "delete"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	$querys[] = "pp_agent=".$_GET['pp_agent'];


	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_popup where pp_idx = '".$_POST['pp_idx']."'");

	sql_query("delete from rb_popup where pp_idx = '$pp_idx'");


	foreach($field_arr as $k => $v){
		@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/".$data[$v]);
	}


	alert("팝업이 삭제 되었습니다.", "./popup_list.php?$query");
}

alert("잘못된 접근입니다.", "./popup_list.php?$query");
?>