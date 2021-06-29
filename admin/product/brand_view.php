<?php
$menu_code = "400150";
$menu_mode = "v";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'product/brand_view.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'bottom'  =>'inc/bottom.tpl',
	'second_menu' => 'inc/second_menu.tpl',
));

$tpl->assign('page_title', get_txt_from_data($_cfg['menu_data'], $menu_code, "menu_code", "menu_name")."- 보기");
$tpl->assign('mode', "update");


$search_query = "";


$querys = array();
$querys[] = "page=".$page;
$querys[] = "sca=".$_GET['sca'];
$querys[] = "stx=".$_GET['stx'];

$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";


$data = sql_fetch("select * from rb_brand as mn where mn.br_idx = '$br_idx' $search_query");

if(!$data['br_idx']) alert("없는 브랜드입니다.");

$tpl->assign('data', $data);


$tpl->print_('body');
include "../inc/_tail.php";
?>