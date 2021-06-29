<?php
$menu_code = "100200";
$menu_mode = "v";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'member/seller_list.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'bottom'  =>'inc/bottom.tpl',
	'second_menu' => 'inc/second_menu.tpl',
));

$tpl->assign('page_title', get_txt_from_data($_cfg['menu_data'], $menu_code, "menu_code", "menu_name")."- 목록");

$querys = array();
$querys_page = array();



// GET 값 구해서 각각 필요에 따라 파라미터 만들기
if($_GET['page']){
	$page = $_GET['page'];
}else{
	$page = 1;
}
$querys[] = "page=".$page;

if($_GET['sca'] && $_GET['stx']){
	switch($_GET['sca']){
		default:
			$search_query .= " and ".$_GET['sca']." like '%".$_GET['stx']."%' ";
		break;
	}
}

$querys[] = "sca=".$_GET['sca'];
$querys_page[] = "sca=".$_GET['sca'];
$querys[] = "stx=".$_GET['stx'];
$querys_page[] = "stx=".$_GET['stx'];


if($_GET['mb_status'] != ""){
	$search_query .= " and m.mb_status = '".$_GET['mb_status']."' ";
}
$querys[] = "mb_status=".$_GET['mb_status'];
$querys_page[] = "mb_status=".$_GET['mb_status'];


if($_GET['mb_vet'] != ""){
	$search_query .= " and m.mb_vet = '".$_GET['mb_vet']."' ";
}
$querys[] = "mb_vet=".$_GET['mb_vet'];
$querys_page[] = "mb_vet=".$_GET['mb_vet'];


$query_order = (is_array($querys) && custom_count($querys) > 0) ? implode("&", $querys) : "";
$order_query = "order by m.mb_idx desc";
if($order_by != ""){
	$order_query = " order by $order_by ";
}
$querys[] = "order_by=".$_GET['order_by'];
$querys_page[] = "order_by=".$_GET['order_by'];

$query = (is_array($querys) && custom_count($querys) > 0) ? implode("&", $querys) : "";
$query_page = (is_array($querys_page) && custom_count($querys_page) > 0) ? implode("&", $querys_page) : "";

// 전체 데이터수 구하기
$sql_total = "select * from rb_member as m where m.mb_level = ".$_cfg['seller_level']." and mb_status > 0 $search_query";
$total = sql_total($sql_total);

//탈퇴회원수
$sql_out_total = "select * from rb_member as m where m.mb_level = ".$_cfg['seller_level']." and mb_status = 2 $search_query";
$out_total = sql_total($sql_out_total);
$tpl->assign('out_total', $out_total); 

//$total = 2367;
//$page = 46;
// 페이징 만들기 시작
$arr = array('total' => $total,
             'page' => $page,
             'row' => $_cfg['admin_paging_row'],
             'scale' => $_cfg['admin_paging_scale'],
             'center' => $_cfg['admin_paging_center'],
			 'link' => $query_page,
			 'page_name' => ""
        );

try {$paging = C::paging($arr); }
catch (Exception $e) {
    print 'LINE: '.$e->getLine().' '
                  .C::get_errmsg($e->getmessage());
    exit;
}
$tpl->assign($paging);
$tpl->assign('paging_data', $paging);

// 페이징 만들기 끝

if($total){
	$limit_query = " limit ".$paging['query']->limit." offset ".$paging['query']->offset;

	$sql = "select m.* from rb_member as m where m.mb_level = ".$_cfg['seller_level']." and mb_status > 0 $search_query $order_query $limit_query";
	$data = sql_list($sql);

	$tpl->assign('data', $data); 
}

$tpl->print_('body');
include "../inc/_tail.php";
?> 
