<?php
$page_name = "index";
$page_option = "bgwhite header-white";
include "../inc/_common.php";

$t_menu = 1;
$l_menu = 1;

if ($user_agent != "app") {
	goto_login();
}

$tpl=new Template;
$tpl->define(array(
	'contents'  =>'company/measuring_body_fat_insert.tpl',

	'left'  =>	'inc/cs_left.tpl',

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

if ($is_profile == false) {
	alert($_lang['health']['text_0799']);
}

$tpl->assign('mode', 'insert');

include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>