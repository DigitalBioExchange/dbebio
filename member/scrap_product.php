<?php
include "../inc/_common.php";
include "../_inc/_product_config.php";

$t_menu = 8;
$l_menu = 2;

goto_login();

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'member/scrap_product.tpl',

	'left'  =>	'inc/mypage_left.tpl',

	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
));

$product_config = $_cfg['product_config'];
$tpl->assign('product_config', $product_config); 

$querys = array();
$querys_page = array();

// GET 값 구해서 각각 필요에 따라 파라미터 만들기
if($_GET['page']){
	$page = $_GET['page'];
}else{
	$page = 1;
}
$querys[] = "page=".$page;

if(is_array($product_config['category']) && custom_count($product_config['category']) > 0){
	if($_GET['pd_category']){
		$search_query .= "  and b.pd_category = '".$_GET['pd_category']."' ";
		$querys[] = "pd_category=".$_GET['pd_category'];
		$querys_page[] = "pd_category=".$_GET['pd_category'];
	}
}

if($_GET['sca'] && $_GET['stx']){
	switch($_GET['sca']){
		case "bd" : 
			$search_query .= " and (b.pd_title like '%".$_GET['stx']."%' or b.pd_contents like '%".$_GET['stx']."%') ";
		break;
		case "bd2" : 
			$search_query .= " and (b.pd_title like '%".$_GET['stx']."%' or b.pd_link1 like '%".$_GET['stx']."%') ";
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

$order_query = "order by sc.sc_idx desc";

$query = (is_array($querys) && custom_count($querys) > 0) ? implode("&", $querys) : "";
$query_page = (is_array($querys_page) && custom_count($querys_page) > 0) ? implode("&", $querys_page) : "";

// 전체 데이터수 구하기
$sql_total = "select * from rb_product_scrap as sc left join rb_product as b on sc.pd_idx = b.pd_idx where sc.mb_id = '".$member['mb_id']."' $search_query";
$total = sql_total($sql_total);


//$total = 2367;
//$page = 46;
// 페이징 만들기 시작
$arr = array('total' => $total,
             'page' => $page,
             'row' => 10,
             'scale' => 5,
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

	$sql = "select * from rb_product_scrap as sc left join rb_product as b on sc.pd_idx = b.pd_idx where sc.mb_id = '".$member['mb_id']."' $search_query $order_query $limit_query";
	$data = sql_list($sql);

	
	for($i=0;$i<custom_count($data);$i++){
		$pd_idx = $data[$i]['pd_idx'];
		$data[$i]['product_config'] = $_cfg['product_config'][$data[$i]['bc_code']];
		if($product_config['is_file'] > 0){
			$pd_file_data = sql_fetch("select * from rb_product_file where pd_idx = '$pd_idx' and fi_num = 0");
			$data[$i]['fi_name'] = $pd_file_data['fi_name'];
			$data[$i]['fi_name_org'] = $pd_file_data['fi_name_org'];
			$data[$i]['fi_idx'] = $pd_file_data['fi_idx'];

		}
	}

	$tpl->assign('data', $data); 
}

include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>