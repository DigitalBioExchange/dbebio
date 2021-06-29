<?php
$menu_code = "420200";
$menu_mode = "w";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'main/banner_insert.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'bottom'  =>'inc/bottom.tpl',
	'second_menu' => 'inc/second_menu.tpl',
));

$tpl->assign('page_title', get_txt_from_data($_cfg[menu_data], $menu_code, "menu_code", "menu_name")."- 등록");
$tpl->assign('mode', "insert");

$tpl->assign('bn_loc', $bn_loc);

$search_query = "";


$querys = array();
$querys[] = "page=".$page;
$querys[] = "sca=".$_GET[sca];
$querys[] = "stx=".$_GET[stx];
$querys[] = "bn_agent=".$_GET[bn_agent];

$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";


$tpl->print_('body');
include "../inc/_tail.php";
?> 
