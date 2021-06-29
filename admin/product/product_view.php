<?php
$menu_code = "400100";
$menu_mode = "v";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'product/product_view.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'bottom'  =>'inc/bottom.tpl',
	'second_menu' => 'inc/second_menu.tpl',
));

$tpl->assign('page_title', get_txt_from_data($_cfg['menu_data'], $menu_code, "menu_code", "menu_name")."- 보기");
$tpl->assign('mode', "update");

$product_config = $_cfg['product_config'];
$tpl->assign('product_config', $product_config); 

$search_query = "";


$querys = array();
$querys[] = "page=".$page;
$querys[] = "sca=".$_GET['sca'];
$querys[] = "stx=".$_GET['stx'];
$querys[] = "c1_idx=".$_GET['c1_idx'];
$querys[] = "c2_idx=".$_GET['c2_idx'];
$querys[] = "c3_idx=".$_GET['c3_idx'];
$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

if($is_seller){
	$search_query .= " and shop_id = '".$member['mb_id']."' ";
}

$data = sql_fetch("select * from rb_product as p where p.pd_idx = '$pd_idx' $search_query");

if(!$data['pd_idx']) alert("없는 상품입니다.");


/*
//조회수 체크
if($data['mb_id'] != $member['mb_id']){
	$chk = sql_fetch("select * from rb_product_history where pd_idx = '$pd_idx' and (mb_id = '$member['mb_id']' or bh_ip = '$_SERVER['REMOTE_ADDR']')");
	if(!$chk['bh_idx']){
		sql_query("insert into rb_product_history set pd_idx = '$pd_idx', bc_code = '$data['bc_code']', mb_id = '$member['mb_id']', bh_ip = '$_SERVER['REMOTE_ADDR']', bh_regdate = now()");
		$data['pd_view_cnt'] = $data['pd_view_cnt'] + 1;
		sql_query("update rb_product set pd_view_cnt = pd_view_cnt + 1 where pd_idx = '$pd_idx'");

	}
}
*/

$tpl->assign('data', $data);


$pd_file_data = sql_list("select * from rb_product_file where pd_idx = '$pd_idx' order by fi_num asc");
$tpl->assign('pd_file_data', $pd_file_data);



$tpl->print_('body');
include "../inc/_tail.php";
?>