<?php
$menu_num = 0;
include "../inc/_common.php";
include "../inc/_head.php";

goto_login();

$l_menu = 4;
$l_menu = 9;

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'member/push.tpl',

	'left'  =>	'inc/mypage_left.tpl',

	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
));



$sido_data = sql_list("select * from rb_sido where 1 order by sd_idx asc");
$tpl->assign('sido_data', $sido_data); 

$tpl->print_('body');
include "../inc/_tail.php";
?>