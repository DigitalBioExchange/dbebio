<?php
include "../inc/_common.php";

$t_menu = 8;
$l_menu = 1;

goto_login();


$tpl=new Template;
$tpl->define(array(
    'contents'  =>'member/refund_account.tpl',

	'left'  =>	'inc/mypage_left.tpl',

	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',

	'my_top'  => 'inc/my_top.tpl',
));

$tpl->assign('mode', "update");


include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>