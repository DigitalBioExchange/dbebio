<?php
$page_name = "Member_mobile_type";
$page_option = "bgwhite header-white";
$log = "out";
include "../inc/_common.php";

$t_menu = 9;
$l_menu = 2;

already_logged();

// $to_num = "821040130926";
// $msg = "문자테스트 다시 보내봄 /n 2343434";
// $rslt = infobip_sms($to_num, $msg);

// p_arr($rslt);exit;




$tpl=new Template;
$tpl->define(array(
	'contents'  =>'member/join_form.tpl',

	'left'  =>	'inc/member_left.tpl',

	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',

	'm_form'  => 'member/m_form.tpl',
));

$tpl->assign('mode', "insert");

$sql = "select * from rb_country_tel_light";
$data_tel = sql_list($sql);
$tpl->assign('data_tel', $data_tel);



include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>