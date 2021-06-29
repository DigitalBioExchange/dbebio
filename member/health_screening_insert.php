<?php
$page_name = "Health_screening_insert";
$page_option = "header-white";

include "../inc/_common.php";

if ($user_agent != "app") {
	$gnb = "4";
	goto_login();
}

$t_menu = 1;
$l_menu = 1;

$tpl=new Template;
$tpl->define(array(
	'contents'  =>'member/health_screening_insert.tpl',

	'left'  =>	'inc/member_left.tpl',

	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
));

$tpl->assign('mode', 'insert');

include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>