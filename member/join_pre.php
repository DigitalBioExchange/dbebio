<?php
$page_name = "member";
include "../inc/_common.php";

$t_menu = 9;
$l_menu = 2;

already_logged();

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'member/join_pre.tpl',

	'left'  =>	'inc/member_left.tpl',

	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
));

//통합로그인 관련
$this_is_login_page = 1;
$urlParam['acesTkn'] = isset($_POST['acesTkn']) ? "&acesTkn={$_POST['acesTkn']}" : "";
$tpl->assign('urlParam', $urlParam);

include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>