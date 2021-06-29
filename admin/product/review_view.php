<?php
$menu_code = "400302";
$menu_mode = "v";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'product/review_view.tpl',
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

if($is_seller){
	$search_query .= " and p.shop_id = '".$member['mb_id']."' ";
}


$data = sql_fetch("select * from rb_product_review as q left join rb_product as p on q.pd_idx = p.pd_idx where q.pr_idx = '$pr_idx' $search_query");

if(!$data['pr_idx']) alert("없는 리뷰입니다.");

$tpl->assign('data', $data);


$tpl->print_('body');
include "../inc/_tail.php";
?>