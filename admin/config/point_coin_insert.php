<?php
$menu_code = "110300";
$menu_mode = "w";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'config/point_coin_insert.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'bottom'  =>'inc/bottom.tpl',
	'second_menu' => 'inc/second_menu.tpl',
));

$tpl->assign('page_title', get_txt_from_data($_cfg['menu_data'], $menu_code, "menu_code", "menu_name")."- 등록");
$tpl->assign('mode', "insert");



$search_query = "";


$querys = array();
$querys[] = "page=".$page;
$querys[] = "sca=".$_GET['sca'];
$querys[] = "stx=".$_GET['stx'];
$querys[] = "ps_status=".$_GET['ps_status'];

$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

//코인시세 표기
$sql = "select * from rb_coin_price where 1 order by cp_idx desc limit 0, 1";
$data_coin = sql_fetch($sql);
$tpl->assign('cp_price', $data_coin['cp_price']); 


$tpl->print_('body');
include "../inc/_tail.php";
?> 
