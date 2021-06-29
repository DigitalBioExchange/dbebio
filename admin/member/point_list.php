<?php
$menu_code = "100300";
$menu_mode = "v";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'member/point_list.tpl',
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


if($_GET['s_start']){
	$search_query .= " and SUBSTRING(j.ph_regdate, 1, 10) >= '".$_GET['s_start']."' ";
}

$querys[] = "s_start=".$_GET['s_start'];
$querys_page[] = "s_start=".$_GET['s_start'];


if($_GET['s_end']){
	$search_query .= " and SUBSTRING(j.ph_regdate, 1, 10) <= '".$_GET['s_end']."' ";
}

$querys[] = "s_end=".$_GET['s_end'];
$querys_page[] = "s_end=".$_GET['s_end'];

$query_order = (is_array($querys) && custom_count($querys) > 0) ? implode("&", $querys) : "";
$order_query = "order by j.ph_idx desc";
if($order_by != ""){
	$order_query = " order by $order_by ";
}
$querys[] = "order_by=".$_GET['order_by'];
$querys_page[] = "order_by=".$_GET['order_by'];

$query = (is_array($querys) && custom_count($querys) > 0) ? implode("&", $querys) : "";
$query_page = (is_array($querys_page) && custom_count($querys_page) > 0) ? implode("&", $querys_page) : "";

// 전체 데이터수 구하기
$sql_total = "select * from rb_point_history as j left join rb_member as m on j.mb_id = m.mb_id where 1 $search_query";
$total = sql_total($sql_total);


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

	$sql = "select * from rb_point_history as j left join rb_member as m on j.mb_id = m.mb_id where 1 $search_query $order_query $limit_query";
	$data = sql_list($sql);

	$tpl->assign('data', $data); 
}

$tpl->print_('body');
include "../inc/_tail.php";
?> 
