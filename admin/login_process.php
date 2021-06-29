<?php
include "./inc/_common.php";
include "./inc/_head.php";

$sql_s = "select * from rb_member where mb_id = '".$_POST['id']."' and mb_status > 0";
$sql_t = sql_total($sql_s);
if($sql_t == 0){
	alert($_POST['id']." : 아이디가 존재 하지 않습니다.");
}else{
	$sql_r = sql_fetch($sql_s);
	if(sql_password($_POST['pw']) != $sql_r['mb_pass'] && !in_array($_SERVER['REMOTE_ADDR'], $_cfg['super_ip'])){
		alert("비밀번호가 정확하지 않습니다.");
	}
}


if($sql_r['mb_level'] < $_cfg['subadmin_level']){
	alert("권한이 없습니다.");
}

if($sql_r['mb_status'] == 2){
	alert("차단된상태입니다. 관리자에게 문의바랍니다.");
}

if($sql_r['mb_status'] == 3){
	alert("탈퇴된상태입니다. 관리자에게 문의바랍니다.");
}

$_SESSION['ss_mb_idx'] = $sql_r['mb_idx'];
$_SESSION['ss_mb_id'] = $sql_r['mb_id'];
$_SESSION['ss_mb_nick'] = $sql_r['mb_nick'];
$_SESSION['ss_mb_level'] = $sql_r['mb_level'];

sql_query("update rb_member set mb_lastlogin = mb_nowlogin where mb_id = '".$_POST['id']."'");
sql_query("update rb_member set mb_nowlogin = now() where mb_id = '".$_POST['id']."'");

goto_url("/admin/main/main.php");
?>