<?php
$menu_num = 0;
include "../inc/_common.php";
include "../inc/_head.php";

goto_login();


$l_menu = 4;
$l_menu = 5;


$tpl=new Template;
$tpl->define(array(
    'contents'  =>'member/payment.tpl',

	'left'  =>	'inc/mypage_left.tpl',

	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
));

$order_payment = $_cfg['order']['payment'][$member['mb_type']];
$tpl->assign('order_payment', $order_payment);

$_cfg['is_pg'] = 1;

$od_num = date("YmdHis").substr(md5(uniqid(rand(), TRUE)), 0, 20);
$tpl->assign('od_num', $od_num);

$tpl->print_('body');
include "../inc/_tail.php";
?>