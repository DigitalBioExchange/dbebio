<?php
$page_name = "member";
include "../inc/_common.php";

$t_menu = 9;
$l_menu = 2;

already_logged();

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'member/join.tpl',

	'left'  =>	'inc/member_left.tpl',

	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
));

$_GET['mb_level'] = (in_array($_GET['mb_level'], array(1, $_cfg['seller_level']))) ? $_GET['mb_level'] : 1;

include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>