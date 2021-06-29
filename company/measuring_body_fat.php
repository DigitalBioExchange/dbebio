<?php
$page_name = "index";
$page_option = "bgwhite header-white";
include "../inc/_common.php";

$t_menu = 1;
$l_menu = 1;

if ($user_agent != "app") {
	$gnb = "2";
	goto_login();
}

$tpl=new Template;
$tpl->define(array(
	'contents'  =>'company/measuring_body_fat.tpl',
	'left'  =>	'inc/member_left.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
));

if ($member['mb_sex'] == 1) {
	$gender = "male";
} else if ($member['mb_sex'] == 2) {
	$gender = "female";
}
$tpl->assign('gender', $gender);
$tpl->assign('idx', $member['mb_idx']);
$tpl->assign('height', $member['mb_height']);
$tpl->assign('birth', $member['mb_birth']);

include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>