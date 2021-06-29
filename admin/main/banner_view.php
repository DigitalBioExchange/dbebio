<?php
$menu_code = "420200";
$menu_mode = "v";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'main/banner_view.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'bottom'  =>'inc/bottom.tpl',
	'second_menu' => 'inc/second_menu.tpl',
));

$tpl->assign('page_title', get_txt_from_data($_cfg[menu_data], $menu_code, "menu_code", "menu_name")."- 보기");
$tpl->assign('mode', "update");

$search_query = "";


$querys = array();
$querys[] = "page=".$page;
$querys[] = "sca=".$_GET[sca];
$querys[] = "stx=".$_GET[stx];
$querys[] = "bn_agent=".$_GET[bn_agent];

$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";


$data = sql_fetch("select * from rb_banner as m where m.bn_idx = '$bn_idx' $search_query");
$tpl->assign('data', $data);
if(!$data[bn_idx]) alert("없는 배너입니다.");


$tpl->print_('body');
include "../inc/_tail.php";
?>