<?php
$menu_code = "100100";
$menu_mode = "w";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

exit;

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'member/member_insert.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'bottom'  =>'inc/bottom.tpl',
	'second_menu' => 'inc/second_menu.tpl',
	'find_address' => 'admin/inc/find_address.tpl',
));

$tpl->assign('page_title', get_txt_from_data($_cfg['menu_data'], $menu_code, "menu_code", "menu_name")."- 등록");
$tpl->assign('mode', "insert");

$search_query = "";


$querys = array();
$querys[] = "page=".$page;
$querys[] = "sca=".$_GET['sca'];
$querys[] = "stx=".$_GET['stx'];
$querys[] = "mb_status=".$_GET['mb_status'];
$querys[] = "mb_level=".$_GET['mb_level'];
$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";


$tpl->print_('body');
include "../inc/_tail.php";
?> 
