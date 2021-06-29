<?php
$menu_code = "400401";
$menu_mode = "w";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'product/coupon_issue2.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'bottom'  =>'inc/bottom.tpl',
	'second_menu' => 'inc/second_menu.tpl',
));

$tpl->assign('page_title', get_txt_from_data($_cfg['menu_data'], $menu_code, "menu_code", "menu_name")."- 발급");
$tpl->assign('mode', "issue2");

$data = sql_fetch("select * from rb_coupon as c where c.cp_idx = '$cp_idx' ");
if(!$data['cp_idx']) alert("없는 쿠폰입니다.");
$tpl->assign('data', $data);

//관련상품
$cp_product = array();
if($data['cp_product'] != ""){
	$cp_product_pd_idx_arr = explode(",", $data['cp_product']);
	foreach($cp_product_pd_idx_arr as $k => $v){
		$cp_product[] = sql_fetch("select p.pd_idx, concat(c1.c1_name, if(c2.c2_name != '', concat('>', c2.c2_name), ''), if(c3.c3_name != '', concat('>', c3.c3_name), ''), ' : [', p.pd_name, ']') as txt from rb_product as p left join rb_cate1 as c1 on c1.c1_idx = p.c1_idx left join rb_cate2 as c2 on c2.c2_idx = p.c2_idx left join rb_cate3 as c3 on c3.c3_idx = p.c3_idx where pd_idx = '{$v}'");
	}
	$tpl->assign('cp_product', $cp_product); 
}

$cp_cate = array();
if($data['cp_cate'] != ""){
	$cp_cate_idx_arr = explode(",", $data['cp_cate']);
	foreach($cp_cate_idx_arr as $k => $v){
		$cate_datas_arr = explode(":", $v);
		$c1_data = sql_fetch("select * from rb_cate1 where c1_idx = '".$cate_datas_arr[0]."'");
		$c2_data = ($cate_datas_arr[1]) ? sql_fetch("select * from rb_cate2 where c2_idx = '".$cate_datas_arr[1]."'") : array();
		$c3_data = ($cate_datas_arr[2]) ? sql_fetch("select * from rb_cate2 where c3_idx = '".$cate_datas_arr[2]."'") : array();

		$txt = $c1_data['c1_name'];
		$txt .= ($c2_data['c2_name'] != "") ? ">".$c2_data['c2_name'] : "";
		$txt .= ($c3_data['c3_name'] != "") ? ">".$c3_data['c3_name'] : "";

		$val = $v;

		$temp = array();
		$temp['val'] = $val;
		$temp['txt'] = $txt;

		$cp_cate[] = $temp;
	}
	$tpl->assign('cp_cate', $cp_cate); 
}


$querys = array();
$querys_page = array();

$querys[] = "cp_idx=".$cp_idx;

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

$order_query = "order by cr.cc_idx desc";

$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";
$query_page = (is_array($querys_page) && count($querys_page) > 0) ? implode("&", $querys_page) : "";


// 전체 데이터수 구하기
$sql_total = "select * from rb_coupon_cord_record as cr left join rb_coupon as c on cr.cp_idx = c.cp_idx where 1 $search_query";
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

	$sql = "select * from rb_coupon_cord_record as cr left join rb_coupon as c on cr.cp_idx = c.cp_idx where 1 $search_query $order_query $limit_query";
	$list_data = sql_list($sql);

	$tpl->assign('list_data', $list_data); 
}

$tpl->print_('body');
include "../inc/_tail.php";
?> 
