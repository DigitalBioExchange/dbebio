<?php
$menu_code = "100300";
$menu_mode = "w";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

$tpl=new Template;
$tpl->define(array(
	'contents'  =>'member/point_insert.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'bottom'  =>'inc/bottom.tpl',
	'second_menu' => 'inc/second_menu.tpl',
));

$tpl->assign('page_title', get_txt_from_data($_cfg['menu_data'], $menu_code, "menu_code", "menu_name")."- 추가");
$tpl->assign('mode', "insert");

$search_query = "";


$querys = array();
$querys[] = "page=".$page;
$querys[] = "sca=".$_GET['sca'];
$querys[] = "stx=".$_GET['stx'];
$querys[] = "s_start=".$_GET['s_start'];
$querys[] = "s_end=".$_GET['s_end'];
$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";


$tpl->print_('body');
include "../inc/_tail.php";
?> 
