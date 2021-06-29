<?php
$menu_code = "100200";
$menu_mode = "w";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

$query = $_POST['query'];


$_POST['mb_si_idx'] = substr($_POST['mb_bcode'], 0, 5);
$_POST['mb_sd_idx'] = substr($_POST['mb_bcode'], 0, 2);


$sql_common = "
	mb_name = '".$_POST['mb_name']."',
	mb_hp = '".$_POST['mb_hp']."',
	mb_zip = '".$_POST['mb_zip']."',
	mb_addr1 = '".$_POST['mb_addr1']."',
	mb_addr2 = '".$_POST['mb_addr2']."',

	mb_sido = '".$_POST['mb_sido']."',
	mb_sigungu = '".$_POST['mb_sigungu']."',
	mb_dong = '".$_POST['mb_dong']."',
	mb_x_pos = '".$_POST['mb_x_pos']."',
	mb_y_pos = '".$_POST['mb_y_pos']."',
	mb_bcode = '".$_POST['mb_bcode']."',
	mb_si_idx = '".$_POST['mb_si_idx']."',
	mb_sd_idx = '".$_POST['mb_sd_idx']."',

	mb_sms_y = '".$_POST['mb_sms_y']."',
	mb_mail_y = '".$_POST['mb_mail_y']."',

	mb_vet = '".$_POST['mb_vet']."',

	mb_com_name = '".$_POST['mb_com_name']."',
	mb_worktime_s = '".$_POST['mb_worktime_s']."',
	mb_worktime_e = '".$_POST['mb_worktime_e']."',
	mb_workday = '".$_POST['mb_workday']."',
	mb_workoff = '".$_POST['mb_workoff']."',
	mb_push = '".$_POST['mb_push']."'

";

//이미지체크
$field_arr = array("mb_img1", "mb_img2");
foreach($field_arr as $k => $v){
	if($_FILES[$v]['tmp_name']){
		$timg = @getimagesize($_FILES[$v]['tmp_name']);
		if($timg[2] != 1 && $timg[2] != 2 && $timg[2] != 3) alert("jpg, gif, png 파일만 업로드 가능합니다.");
	}
}

if($_POST['mode'] == "insert"){
	exit;

	$id_chk = sql_fetch("select * from rb_member where mb_id = '".$_POST['mb_id']."'");
	if($id_chk['mb_idx']){
		alert("사용중인 아이디입니다.");
	}


	$sql = "insert into rb_member set
				$sql_common,
				mb_id = '".$_POST['mb_id']."',
				mb_pass = sha2('".$_POST['mb_pass']."', 256),
				mb_regdate = now()
			";
	$sql_q = sql_query($sql);
	$mb_idx = mysql_insert_id();


	alert("회원이 추가 되었습니다.", "./seller_list.php?$query");

}else if($_POST['mode'] == "update" && $_POST['mb_idx']){

	$mb = get_member2($mb_idx);

	if($_POST['mb_pass']){
		$sql_common .= " , mb_pass = sha2('".$_POST['mb_pass']."', 256) ";
	}

	$vet_code_sql = "";
	if(!$mb['mb_vet_code']){
		$max = sql_fetch("select max(mb_vet_code_val) as mx from rb_member where 1");
		$vet_code_sql = " , mb_vet_code_val = '".($max['mx'] + 1)."' , mb_vet_code = 'VET".(sprintf('%04d', ($max['mx'] + 1)))."' ";
	}

	$sql = "update rb_member set
				$sql_common
				$vet_code_sql
				
				where mb_idx = '".$mb_idx."'
			";
	$sql_q = sql_query($sql);

	//이미지저장
	foreach($field_arr as $k => $v){
		if($_FILES[$v]['tmp_name']){
			$src = $_FILES[$v]['tmp_name'];
			$ext = get_file_ext($_FILES[$v]['name']);
			$tgt_name = md5(uniqid(rand(), TRUE)).".".$ext;
			$org_name = $_FILES[$v]['name'];
			$tgt = $_cfg['web_home'].$_cfg['data_dir']."/files/".$tgt_name;

			@move_uploaded_file($src, $tgt);
			@chmod($tgt, 0666);

			sql_query("update rb_member set $v = '$tgt_name', {$v}_org = '$org_name' where mb_idx = '$mb_idx'");

		}
	}



	alert("회원이 수정 되었습니다.", "./seller_list.php?$query");
}else if($_GET['mode'] == "delete" && $_GET['mb_idx']){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	$querys[] = "mb_status=".$_GET['mb_status'];
	$querys[] = "mb_vet=".$_GET['mb_vet'];

	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	delete_member($mb_idx);

	alert("회원이 삭제되었습니다.", "./seller_list.php?$query");
}else if($_POST['mode'] == "all_delete"){

	for($i=0;$i<count($_POST['mb_id']);$i++){
		$mb_id = $_POST['mb_id'][$i];
		delete_member2($mb_id);
	}
	alert("회원이 삭제 되었습니다.", "./seller_list.php?$query");
}

alert("잘못된 접근입니다.", "./seller_list.php?$query");
?>