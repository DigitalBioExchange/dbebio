<?php
$page_name = "member";
include "../inc/_common.php";

$t_menu = 9;
$l_menu = 1;

already_logged();

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'member/login2.tpl',

	'left'  =>	'inc/member_left.tpl',

	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
));


include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>