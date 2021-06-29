<?php
$menu_code = "300110";
$menu_mode = "v";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'main/delivery_list.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'bottom'  =>'inc/bottom.tpl',
	'second_menu' => 'inc/second_menu.tpl',
));

$tpl->assign('page_title', get_txt_from_data($_cfg[menu_data], $menu_code, "menu_code", "menu_name")."- 목록");

$querys = array();
$querys_page = array();



// GET 값 구해서 각각 필요에 따라 파라미터 만들기
if($_GET[page]){
	$page = $_GET[page];
}else{
	$page = 1;
}
$querys[] = "page=".$page;

if($_GET[sca] && $_GET[stx]){
	switch($_GET[sca]){
		case "names" :
			$search_query .= " and (m.mb_name or o.od_name) like '%$_GET[stx]%' ";
		break;
		default:
			$search_query .= " and $_GET[sca] like '%$_GET[stx]%' ";
		break;
	}
}

$querys[] = "sca=".$_GET[sca];
$querys_page[] = "sca=".$_GET[sca];
$querys[] = "stx=".$_GET[stx];
$querys_page[] = "stx=".$_GET[stx];

if($_GET[od_status] != ""){
	$search_query .= " and o.od_status = '$_GET[od_status]' ";
}
$querys[] = "od_status=".$_GET[od_status];
$querys_page[] = "od_status=".$_GET[od_status];


if($_GET[s_start]){
	$search_query .= " and SUBSTRING($_GET[date_field], 1, 10) >= '$_GET[s_start]' ";
}

$querys[] = "s_start=".$_GET[s_start];
$querys_page[] = "s_start=".$_GET[s_start];


if($_GET[s_end]){
	$search_query .= " and SUBSTRING($_GET[date_field], 1, 10) <= '$_GET[s_end]' ";
}

$querys[] = "s_end=".$_GET[s_end];
$querys_page[] = "s_end=".$_GET[s_end];

$querys[] = "date_field=".$_GET[date_field];
$querys_page[] = "date_field=".$_GET[date_field];

$query_order = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";
$order_query = "order by o.od_idx desc";
if($order_by != ""){
	$order_query = " order by $order_by ";
}
$querys[] = "order_by=".$_GET[order_by];
$querys_page[] = "order_by=".$_GET[order_by];

$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";
$query_page = (is_array($querys_page) && count($querys_page) > 0) ? implode("&", $querys_page) : "";

// 전체 데이터수 구하기
$sql_total = "select * from rb_order_cart as c left join rb_product as p on c.pd_idx = p.pd_idx left join rb_order as o on c.od_idx = o.od_idx where c.ct_status > 0  $search_query";
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

	$sql = "select * from rb_order_cart as c left join rb_product as p on c.pd_idx = p.pd_idx left join rb_order as o on c.od_idx = o.od_idx where c.ct_status > 0 $search_query $order_query $limit_query";
	$data = sql_list($sql);

	for($i=0;$i<count($data);$i++){
		$_pd_idx = $data[$i][pd_idx];
		if($_pd_idx){
			$_pd_file_data = sql_fetch("select * from rb_product_file where pd_idx = '$_pd_idx' and fi_num = 0");
			$data[$i][fi_name] = $_pd_file_data[fi_name];
			$data[$i][fi_name_org] = $_pd_file_data[fi_name_org];
			$data[$i][fi_idx] = $_pd_file_data[fi_idx];
		}

		$data[$i][deli_option] = array();
		$data[$i][to_deli_cnt] = $data[$i][ct_cnt] - $data[$i][ct_deli_cnt];
		for($j=0;$j<=$data[$i][to_deli_cnt];$j++){
			$temp = array();
			$temp[val] = $j;
			$temp[txt] = number_format($j)."개";

			$data[$i][deli_option][] = $temp;
		}

		$_ct_idx = $data[$i][ct_idx];
		$deli_list = sql_list("select * from rb_delivery_cart as dc left join rb_delivery as d on dc.de_idx = d.de_idx where ct_idx = '$_ct_idx' order by d.de_idx asc");
		$data[$i][deli_list] = $deli_list;
	}

	$tpl->assign('data', $data); 
}

$tpl->print_('body');
include "../inc/_tail.php";
?> 
