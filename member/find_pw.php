<?php
$page_name = "Member_mobile_type";
$page_option = "bgwhite header-white";
include "../inc/_common.php";

$t_menu = 9;
$l_menu = 4;

already_logged();

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'member/find_pw.tpl',
	'left'  =>	'inc/member_left.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
));

unset($_SESSION['find_idpw_id']);


$sql = "select * from rb_country_tel_light";
$data_tel = sql_list($sql);
$tpl->assign('data_tel', $data_tel);


include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>