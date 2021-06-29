<?php
$menu_code = "400100";
$menu_mode = "v";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'product/product_list.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'bottom'  =>'inc/bottom.tpl',
	'second_menu' => 'inc/second_menu.tpl',
));

$tpl->assign('page_title', get_txt_from_data($_cfg['menu_data'], $menu_code, "menu_code", "menu_name")."- 목록");

$product_config = $_cfg['product_config'];
$tpl->assign('product_config', $product_config); 

$querys = array();
$querys_page = array();
$search_query = "";

// GET 값 구해서 각각 필요에 따라 파라미터 만들기
if($_GET['page']){
	$page = $_GET['page'];
}else{
	$page = 1;
}
$querys[] = "page=".$page;

if($_GET['c1_idx'] != ""){
	$search_query .= " and p.c1_idx = '".$_GET['c1_idx']."' ";
}
$querys[] = "c1_idx=".$_GET['c1_idx'];
$querys_page[] = "c1_idx=".$_GET['c1_idx'];

if($_GET['c2_idx'] != ""){
	$search_query .= " and p.c2_idx = '".$_GET['c2_idx']."' ";
}
$querys[] = "c2_idx=".$_GET['c2_idx'];
$querys_page[] = "c2_idx=".$_GET['c2_idx'];

if($_GET['c3_idx'] != ""){
	$search_query .= " and p.c3_idx = '".$_GET['c3_idx']."' ";
}
$querys[] = "c3_idx=".$_GET['c3_idx'];
$querys_page[] = "c3_idx=".$_GET['c3_idx'];

if($_GET['sca'] && $_GET['stx']){
	switch($_GET['sca']){
		case "bd" : 
			$search_query .= " and (p.c2_name like '%".$_GET['stx']."%' or p.c2_contents like '%".$_GET['stx']."%') ";
		break;
		default:
			$search_query .= " and ".$_GET['sca']." like '%".$_GET['stx']."%' ";
		break;
	}
}

$querys[] = "sca=".$_GET['sca'];
$querys_page[] = "sca=".$_GET['sca'];
$querys[] = "stx=".$_GET['stx'];
$querys_page[] = "stx=".$_GET['stx'];


$order_query = "order by p.pd_idx desc";

$query = (is_array($querys) && custom_count($querys) > 0) ? implode("&", $querys) : "";
$query_page = (is_array($querys_page) && custom_count($querys_page) > 0) ? implode("&", $querys_page) : "";


if($is_seller){
	$search_query .= " and p.shop_id = '".$member['mb_id']."' ";
}

// 전체 데이터수 구하기
$sql_total = "select * from rb_product as p where 1 $search_query";
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

	$sql = "select * from rb_product as p where 1 $search_query $order_query $limit_query";
	$data = sql_list($sql);

	for($i=0;$i<custom_count($data);$i++){
		$_pd_idx = $data[$i]['pd_idx'];
		if($_pd_idx){
			$_pd_file_data = sql_fetch("select * from rb_product_file where pd_idx = '$_pd_idx' and fi_num = 1");
			$data[$i]['fi_name'] = $_pd_file_data['fi_name'];

		}
	}

	$tpl->assign('data', $data); 
}


$tpl->print_('body');
include "../inc/_tail.php";
?> 
