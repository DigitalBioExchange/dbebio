<?php
$menu_num = 0;
include "../inc/_common.php";
include "../inc/_head.php";

already_logged();

// p_arr($_POST);exit;

$session_id = session_id();
$sql_chk = "select * from rb_certi where ce_session = '".$session_id."' and ce_num = '".$ce_num."' ";
$data_chk = sql_fetch($sql_chk);

if ($data_chk['ce_idx']) {
	$_POST['mb_hp'] = split_tel_number($_POST['mb_hp']);
	$sql_check = "select * from rb_member where mb_hp = '".$_POST['mb_hp']."' and mb_n_telnum = '".$_POST['mb_n_telnum']."' and mb_status = 1 ";
	$data_check = sql_fetch($sql_check);

	if ($data_check['mb_idx']) {
		$sql_upd = "update rb_member set mb_pass = sha2('".$_POST['mb_pass']."', 256) where mb_idx = '".$data_check['mb_idx']."' ";
		sql_query($sql_upd);
		alert($_lang['tpl_member']['text_0229'], "/member/login.php");
	} else {
		alert($_lang['inc']['text_0746']);
	}

} else {
	alert($_lang['inc']['text_0748']);
}



include "../inc/_tail.php";
?>