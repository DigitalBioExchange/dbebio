<?php
$menu_num = 0;
include "../inc/_common.php";
include "../inc/_head.php";


$t_menu = 5;
$l_menu = 13;


$tpl=new Template;
$tpl->define(array(
    'contents'  =>'shop/review_view.tpl',

	'left'  =>	'inc/cs_left.tpl',

	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
));

$tpl->assign('page_title', '포토리뷰');

$querys = array();
$querys[] = "page=".$page;
$querys[] = "c1_idx=".$_GET[c1_idx];
$querys[] = "c2_idx=".$_GET[c2_idx];
$querys[] = "c3_idx=".$_GET[c3_idx];
$querys[] = "stx=".$_GET[stx];


$order_query = "order by r.pr_idx desc";

$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

$sql = "select * from rb_product_review as r left join rb_product as p on r.pd_idx = p.pd_idx where r.pr_img != '' and pr_idx = '$pr_idx'";
$data = sql_fetch($sql);

if(!$data[pr_idx]) alert("없는 포토리뷰입니다.");


$tpl->assign('data', $data);

$pd_idx = $data[pd_idx];
$pd_file_data = sql_list("select * from rb_product_file where pd_idx = '$pd_idx' order by fi_num asc");
$tpl->assign('pd_file_data', $pd_file_data);


$tpl->print_('body');
include "../inc/_tail.php";
?>