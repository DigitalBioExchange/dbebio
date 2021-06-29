<?php
$page_option = "header-white";
include "../inc/_common.php";

$t_menu = 8;
$l_menu = 1;

if ($user_agent != "app") {
	$gnb = "4";
	goto_login();
}


if($is_admin){
	alert($_lang['member']['text_0766'], "/index.php");
}

$tpl=new Template;
$tpl->define(array(
	'contents'  =>'member/setting_metamask.tpl',

	'left'  =>	'inc/member_left.tpl',

	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',

	'm_form'  => 'member/m_form.tpl',
));

// $tpl->assign('mode', "update");

// $session_id = session_id();
// $sql_chk = "select * from rb_certi where ce_session = '$session_id' and ce_end > '".date("Y-m-d H:i:s", strtotime("-3 minutes"))."' ";
// echo $sql_chk;
// $data_chk = sql_fetch($sql_chk);
// p_arr($data_chk); exit;

include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>