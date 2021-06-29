<?php
$page_name = "not-aside";
$mobile_page_name = "";
include "../inc/_common.php";


$t_menu = 0;
$l_menu = 0;

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'shop/icon_list.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
));
$product_config = $_cfg['product_config'];
$tpl->assign('product_config', $product_config); 

if(!get_txt_from_data($_cfg['product']['ic_type'], $_GET[ic_type])){
	$_GET[ic_type] = 1;
}
$ic_type = $_GET[ic_type];

$ic_title = get_txt_from_data($_cfg['product']['ic_type'], $_GET[ic_type]);
$tpl->assign('ic_type', $ic_type);
$tpl->assign('ic_title', $ic_title);

$querys = array();
$querys_page = array();


$search_query = "";

$search_query .= " and ic.ic_type = '$ic_type' ";
$querys[] = "ic_type=".$ic_type;

$order_query = "order by ic.ic_sort asc";

$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";
$query_page = (is_array($querys_page) && count($querys_page) > 0) ? implode("&", $querys_page) : "";


$sql = "select * from rb_icon as ic left join rb_product as p on ic.pd_idx = p.pd_idx where p.pd_use = 1 $search_query $order_query ";
$data = sql_list($sql);
if($product_config['is_file'] > 0){
	for($i=0;$i<count($data);$i++){
		$_pd_idx = $data[$i][pd_idx];
		if($_pd_idx){
			$_pd_file_data = sql_fetch("select * from rb_product_file where pd_idx = '$_pd_idx' and fi_num = 0");
			$data[$i][fi_name] = $_pd_file_data[fi_name];
			$data[$i][fi_name_org] = $_pd_file_data[fi_name_org];
			$data[$i][fi_idx] = $_pd_file_data[fi_idx];

		}
	}
}
$tpl->assign('data', $data); 

include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>