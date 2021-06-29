<?php
include "./inc/_common.php";
include "./inc/_head.php";

$is_no_menu = 1;
session_destroy();
$is_admin = false;
$is_super = false;
$is_member = false;
$member = array();

$tpl=new Template;

$tpl->define(array(
	'contents'  =>'login.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'bottom'  =>'inc/bottom.tpl',
	'second_menu' => 'inc/second_menu.tpl',
));

$tpl->assign('page_title', "관리자로그인");


$tpl->print_('body');
include "./inc/_tail.php";
?> 
