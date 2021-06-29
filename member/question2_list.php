<?php
include "../inc/_common.php";

$t_menu = 8;
$l_menu = 1;

goto_login();

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'member/question2_list.tpl',

	'left'  =>	'inc/mypage_left.tpl',

	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
	'my_top'  => 'inc/my_top.tpl',
));


$querys = array();
$search_query = "";

if($_GET['s_period'] == "" && $_GET['s_start'] == "" && $_GET['s_end'] == ""){
	$_GET['s_period'] = "1";
	$_GET['s_start'] = date("Y-m-d", strtotime("-".$_GET['s_period']." months", time()));
	$_GET['s_end'] = date("Y-m-d");
}else if($_GET['s_period'] != "" && $_GET['s_start'] == "" && $_GET['s_end'] == ""){
	$_GET['s_start'] = date("Y-m-d", strtotime("-".$_GET['s_period']." months", time()));
	$_GET['s_end'] = date("Y-m-d");
}
$querys[] = "s_period=".$_GET['s_period'];

if($_GET['s_start']){
	$search_query .= " and SUBSTRING(a.pq_regdate, 1, 10) >= '".$_GET['s_start']."' ";
}
$querys[] = "s_start=".$_GET['s_start'];


if($_GET['s_end']){
	$search_query .= " and SUBSTRING(a.pq_regdate, 1, 10) <= '".$_GET['s_end']."' ";
}
$querys[] = "s_end=".$_GET['s_end'];



$order_query = "order by a.pq_idx desc";

$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

$sql = "select a.*, b.pd_img, b.pd_name from rb_product_qna as a left join rb_product as b on a.pd_idx = b.pd_idx where a.mb_id = '".$member['mb_id']."' $search_query $order_query";
$data = sql_list($sql);

$tpl->assign('data', $data); 

include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";



$question2 = sql_total("select * from rb_product_qna where mb_id = '".$member['mb_id']."' and pq_regdate >= '".date('Y-m-d H:i:s', strtotime("-1 months", time()))."' ");
?>