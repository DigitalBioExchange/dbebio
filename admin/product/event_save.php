<?php
$menu_code = "800020";
$menu_mode = "w";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

//p_arr($_POST);exit;

$query = $_POST['query'];

$sql_common = "
	wi_name = '".$_POST['wi_name']."',
	wi_status2 = '".$_POST['wi_status2']."',
	wi_sdate = '".$_POST['wi_sdate']."',
	wi_edate = '".$_POST['wi_edate']."',
	wi_winner = '".$_POST['wi_winner']."',
	wi_contents = '".$_POST['wi_contents']."'
";

//이미지체크
$field_arr = array("wi_img1");
foreach($field_arr as $k => $v){
	if($_FILES[$v]['tmp_name']){
		$timg = @getimagesize($_FILES[$v]['tmp_name']);
		if($timg[2] != 1 && $timg[2] != 2 && $timg[2] != 3) alert("jpg, gif, png 파일만 업로드 가능합니다.");
	}
}

if($_POST['mode'] == "insert"){

	$sql = "insert into rb_event set
				$sql_common
				, wi_regdate = now()
			";
	$sql_q = sql_query($sql);
	$wi_idx = mysql_insert_id();

	//파일저장
	foreach($field_arr as $k => $v){
		if($_FILES[$v]['tmp_name']){
			$src = $_FILES[$v]['tmp_name'];
			$ext = get_file_ext($_FILES[$v]['name']);
			$tgt_name = md5(uniqid(rand(), TRUE)).".".$ext;
			$org_name = $_FILES[$v]['name'];
			$tgt = $_cfg['web_home'].$_cfg['data_dir']."/files/".$tgt_name;

			Chk_exif_WH($src, $tgt);

			sql_query("update rb_event set $v = '$tgt_name', {$v}_org = '$org_name' where wi_idx = '$wi_idx'");

		}
	}

	alert("이벤트가 추가되었습니다.", "./event_list.php?$query");
}else if($_POST['mode'] == "update"){

	$data = sql_fetch("select * from rb_event as mn where mn.wi_idx = '$wi_idx' $search_query");

	if(!$data['wi_idx']) alert("없는 이벤트입니다.");


	$sql = "update rb_event set
				$sql_common
				where wi_idx = '".$_POST['wi_idx']."'
			";
	$sql_q = sql_query($sql);
	$wi_idx = $_POST['wi_idx'];

	//파일삭제
	foreach($field_arr as $k => $v){
		if($_FILES[$v]['tmp_name']){
			sql_query("update rb_event set $v = '', {$v}_org = '' where wi_idx = '$wi_idx'");
			@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/".$data[$v]);
		}
	}

	//파일저장
	foreach($field_arr as $k => $v){
		if($_FILES[$v]['tmp_name']){
			$src = $_FILES[$v]['tmp_name'];
			$ext = get_file_ext($_FILES[$v]['name']);
			$tgt_name = md5(uniqid(rand(), TRUE)).".".$ext;
			$org_name = $_FILES[$v]['name'];
			$tgt = $_cfg['web_home'].$_cfg['data_dir']."/files/".$tgt_name;

			Chk_exif_WH($src, $tgt);

			sql_query("update rb_event set $v = '$tgt_name', {$v}_org = '$org_name' where wi_idx = '$wi_idx'");

		}
	}



	alert("이벤트가 수정되었습니다.", "./event_view.php?wi_idx=".$_POST['wi_idx']."&$query");
}else if($_GET['mode'] == "delete"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "wi_status1=".$_GET['wi_status1'];
	$querys[] = "wi_status2=".$_GET['wi_status2'];
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_event as mn where mn.wi_idx = '$wi_idx' $search_query");

	if(!$data['wi_idx']) alert("없는 이벤트입니다.");

	//파일삭제
	foreach($field_arr as $k => $v){
		@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/".$data[$v]);
	}


	sql_query("delete from  rb_event  where wi_idx = '$wi_idx'");

	alert("이벤트가 삭제되었습니다.", "./event_list.php?$query");

}

alert("잘못된 접근입니다.", "./event_list.php?$query");
?>