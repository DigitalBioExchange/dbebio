<?php
$page_name = "block-type-328";
$page_option = "bgwhite header-white";
include "../inc/_common.php";

if ($user_agent != "app") {
	$gnb = "4";
	goto_login();
}

$t_menu = 8;
$l_menu = 4;


if($is_admin){
	alert($_lang['member']['text_0773'], "/index.php");
}

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'member/out.tpl',

	'left'  =>	'inc/member_left.tpl',

	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',

	'my_top'  => 'inc/my_top.tpl',
));

$sql = "select * from rb_country_tel_light";
$data_tel = sql_list($sql);
$tpl->assign('data_tel', $data_tel);

include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>