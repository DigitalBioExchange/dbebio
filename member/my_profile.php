<?php
$page_name = "block-type-328";
$page_option = "bgwhite header-white";
include "../inc/_common.php";

if ($user_agent != "app") {
	$gnb = "4";
	goto_login();
}

$t_menu = 1;
$l_menu = 1;

$tpl=new Template;
$tpl->define(array(
	'contents'  =>'member/my_profile.tpl',

	'left'  =>	'inc/member_left.tpl',

	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
));

if ($member['mb_birth'] && $member['mb_sex'] && $member['mb_height'] && $member['mb_nick']) {
	$is_profile = true;
} else {
	$sql_check = "select * from rb_point_history where mb_idx = '".$member['mb_idx']."' and ph_type = 2 ";
	$data_check = sql_fetch($sql_check);

	if ($data_check['ph_idx']) {
		$is_profile = true;
	} else {
		$is_profile = false;		
	}
}

$tpl->assign('mode', 'update_profile');

include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>