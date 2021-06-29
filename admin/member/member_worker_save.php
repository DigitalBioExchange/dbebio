<?php
$menu_code = "100500";
$menu_mode = "w";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

$query = $_POST['query'];

$sql_common = "
	mb_level = '".$_cfg['subadmin_level']."',
	mb_name = '".$_POST['mb_name']."',
	mb_email = '".$_POST['mb_email']."',
	mb_status = '".$_POST['mb_status']."'
";

if($_POST['mode'] == "insert"){
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
	$mb_idx = sql_insert_id();

	// 구글 otp
	$mb_otp_secret = make_otp_secret_string($mb_idx, 1);
	sql_query("update rb_member set mb_otp_secret = '".$mb_otp_secret."' where mb_idx = '".$mb_idx."'");

	alert("관리자가 추가 되었습니다.", "./member_worker_list.php?$query");

}else if($_POST['mode'] == "update" && $_POST['mb_idx']){

	if($_POST['mb_pass']) $sql_pass = ", mb_pass = sha2('".$_POST['mb_pass']."', 256)";

	$sql = "update rb_member set
				$sql_common
				$sql_pass
			where mb_idx = '".$_POST['mb_idx']."'
			";
	$sql_q = sql_query($sql);


	alert("관리자가 수정 되었습니다.", "./member_worker_list.php?$query");
}else if($_GET['mode'] == "delete" && $_GET['mb_idx']){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	$querys[] = "mb_status=".$_GET['mb_status'];

	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	delete_member($mb_idx);

	alert("관리자가 삭제 되었습니다.", "./member_worker_list.php?$query");
}else if($_POST['mode'] == "all_delete"){

	for($i=0;$i<count($_POST['mb_id']);$i++){
		$mb_id = $_POST['mb_id'][$i];
		delete_member2($mb_id);
	}

	regist_admin_log("관리자 삭제");

	alert("관리자가 삭제 되었습니다.", "./member_worker_list.php?$query");
}

alert("잘못된 접근입니다.", "./member_worker_list.php?$query");
?>