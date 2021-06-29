<?php
$menu_code = "400311";
$menu_mode = "w";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'product/qna1_insert.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'bottom'  =>'inc/bottom.tpl',
	'second_menu' => 'inc/second_menu.tpl',
	'find_address' => 'admin/inc/find_address.tpl',
));

$tpl->assign('page_title', get_txt_from_data($_cfg['menu_data'], $menu_code, "menu_code", "menu_name")."- 수정");
$tpl->assign('mode', "update");

$search_query = "";


$querys = array();
$querys[] = "page=".$page;
$querys[] = "sca=".$_GET['sca'];
$querys[] = "stx=".$_GET['stx'];

$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

if($is_seller){
	$search_query .= " and p.shop_id = '".$member['mb_id']."' ";
}

$data = sql_fetch("select * from rb_question as q  where q.qu_idx = '$qu_idx' and qu_type = 1 $search_query");

if(!$data['qu_idx']) alert("없는 문의입니다.");

$tpl->assign('data', $data);


$tpl->print_('body');
include "../inc/_tail.php";
?> 
