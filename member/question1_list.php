<?php
include "../inc/_common.php";

$t_menu = 8;
$l_menu = 1;

goto_login();

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'member/question1_list.tpl',

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
	$search_query .= " and SUBSTRING(qu_regdate, 1, 10) >= '".$_GET['s_start']."' ";
}
$querys[] = "s_start=".$_GET['s_start'];


if($_GET['s_end']){
	$search_query .= " and SUBSTRING(qu_regdate, 1, 10) <= '".$_GET['s_end']."' ";
}
$querys[] = "s_end=".$_GET['s_end'];


$order_query = "order by qu_idx desc";

$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

$sql = "select * from rb_question where mb_id = '".$member['mb_id']."' and qu_type = 1 $search_query $order_query";
$data = sql_list($sql);

$tpl->assign('data', $data); 

include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>