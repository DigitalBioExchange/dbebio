<?php
$page_name = "index";
$page_option = "index";
$logo = "active";
$back = "not";

include "../inc/_common.php";

if ($user_agent != "app") {
	$gnb = "2";
	goto_login();
}

$t_menu = 1;
$l_menu = 1;

$tpl=new Template;
$tpl->define(array(
	'contents'  =>'company/health_care.tpl',

	'left'  =>	'inc/member_left.tpl',

	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
));

if ($member['mb_birth'] && $member['mb_sex'] && $member['mb_height']) {
	$is_profile = true;
} else {
	$is_profile = false;
}

$tpl->assign('is_profile', $is_profile);

include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>