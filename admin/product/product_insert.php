<?php
$menu_code = "400100";
$menu_mode = "w";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'product/product_insert.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'bottom'  =>'inc/bottom.tpl',
	'second_menu' => 'inc/second_menu.tpl',
));

$tpl->assign('page_title', get_txt_from_data($_cfg['menu_data'], $menu_code, "menu_code", "menu_name")."- 등록");
$tpl->assign('mode', "insert");

$product_config = $_cfg['product_config'];
$tpl->assign('product_config', $product_config); 

$search_query = "";


$querys = array();
$querys[] = "page=".$page;
$querys[] = "sca=".$_GET['sca'];
$querys[] = "stx=".$_GET['stx'];
$querys[] = "c1_idx=".$_GET['c1_idx'];
$querys[] = "c2_idx=".$_GET['c2_idx'];
$querys[] = "c3_idx=".$_GET['c3_idx'];
$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

if($product_config['is_file'] > 0){
	$pd_file_data = array();
	for($i=0;$i<$product_config['is_file'];$i++){
		$temp = array();
		$temp['fi_idx'] = "";
		$temp['fi_num'] = "";
		$temp['fi_name'] = "";
		$temp['fi_name_org'] = "";
		$pd_file_data[] = $temp;
	}
}
$tpl->assign('pd_file_data', $pd_file_data);


$tpl->print_('body');
include "../inc/_tail.php";
?> 
