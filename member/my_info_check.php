<?php
include "../inc/_common.php";

$t_menu = 8;
$l_menu = 1;

goto_login();

if($is_admin){
	alert("관리자는 관리자페이지에서만 수정이 가능합니다.." , "/index.php");
}

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'member/my_info_check.tpl',

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