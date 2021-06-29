<?php
$menu_num = 0;
include "../inc/_common.php";
include "../inc/_head.php";

goto_login();


$t_menu = 8;
$l_menu = 8;


$tpl=new Template;
$tpl->define(array(
    'contents'  =>'member/coupon_list.tpl',

	'left'  =>	'inc/mypage_left.tpl',

	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',

	'my_top'  => 'inc/my_top.tpl',
));

$tpl->assign('page_title', '쿠폰목록');

$querys = array();
$search_query = "";

// 2019.12.31 허정진 수정 => s_end 날짜는 쿠폰의 경우 조회기간이 더 길어야 하므로 +1 년정도로 duration을 늘린다
if($_GET['s_period'] == "" && $_GET['s_start'] == "" && $_GET['s_end'] == ""){
	$_GET['s_period'] = "1";
	$_GET['s_start'] = date("Y-m-d", strtotime("-".$_GET['s_period']." months", time()));
	$_GET['s_end'] = date("Y-m-d", strtotime("+1 years"));
}else if($_GET['s_period'] != "" && $_GET['s_start'] == "" && $_GET['s_end'] == ""){
	$_GET['s_start'] = date("Y-m-d", strtotime("-".$_GET['s_period']." months", time()));
	$_GET['s_end'] = date("Y-m-d", strtotime("+1 years"));
}
$querys[] = "s_period=".$_GET['s_period'];

//var_dump($_GET['s_start']);
//var_dump($_GET['s_end']);

// 2019.12.31 허정진 수정 => cr_s_date 의 경우 datetime이 아니기때문에 substring 제외
if($_GET['s_start']){
	//$search_query .= " and SUBSTRING(cr.cr_s_date, 1, 10) >= '".$_GET['s_start']."' ";
	$search_query .= " and cr.cr_s_date >= '".$_GET['s_start']."' ";
}

$querys[] = "s_start=".$_GET['s_start'];


if($_GET['s_end']){
	//$search_query .= " and SUBSTRING(cr.cr_e_date, 1, 10) <= '".$_GET['s_end']."' ";
	$search_query .= " and cr.cr_e_date <= '".$_GET['s_end']."' ";
}

$querys[] = "s_end=".$_GET['s_end'];

$order_query = "order by cr.cr_idx desc";

$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";
$query_page = (is_array($querys_page) && count($querys_page) > 0) ? implode("&", $querys_page) : "";

$sql = "select * from rb_coupon_record as cr left join rb_coupon as c on cr.cp_idx = c.cp_idx where cr.mb_id = '".$member['mb_id']."' and c.cp_use = '1'  and cr.cr_status = '1' $search_query $order_query";

//echo $sql;
//die();
$data = sql_list($sql);

$tpl->assign('data', $data); 


$tpl->print_('body');
include "../inc/_tail.php";
?>