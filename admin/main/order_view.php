<?php
$menu_code = "300100";
$menu_mode = "v";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'main/order_view.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'bottom'  =>'inc/bottom.tpl',
	'second_menu' => 'inc/second_menu.tpl',
));

$tpl->assign('page_title', get_txt_from_data($_cfg['menu_data'], $menu_code, "menu_code", "menu_name")."- 보기");

$querys = array();
$querys[] = "page=".$page;
$querys[] = "sca=".$_GET['sca'];
$querys[] = "stx=".$_GET['stx'];
$querys[] = "od_status=".$_GET['od_status'];
$querys[] = "s_start=".$_GET['s_start'];
$querys[] = "s_end=".$_GET['s_end'];
$querys[] = "date_field=".$_GET['date_field'];

$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

$data = sql_fetch("select *, (select mb_coin_address from rb_member where mb_idx = o.mb_idx) as mb_coin_address from rb_order as o where od_status > 0 and o.od_idx = '$od_idx' $search_query");
$tpl->assign('data', $data);
if(!$data['od_idx']) alert("없는 주문입니다.");

$cart = sql_list("select * from rb_order_cart as c left join rb_product as p on c.pd_idx = p.pd_idx where c.od_idx = '$od_idx' order by c.ct_idx asc");

for($i=0;$i<count($cart);$i++){
	$_pd_idx = $cart[$i]['pd_idx'];
	if($_pd_idx){
		$_pd_file_data = sql_fetch("select * from rb_product_file where pd_idx = '$_pd_idx' and fi_num = 0");
		$cart[$i]['fi_name'] = $_pd_file_data['fi_name'];
		$cart[$i]['fi_name_org'] = $_pd_file_data['fi_name_org'];
		$cart[$i]['fi_idx'] = $_pd_file_data['fi_idx'];
	}

	$cart[$i]['deli_option'] = array();
	$cart[$i]['to_deli_cnt'] = $cart[$i]['ct_cnt'] - $cart[$i]['ct_deli_cnt'];
	for($j=0;$j<=$cart[$i]['to_deli_cnt'];$j++){
		$temp = array();
		$temp['val'] = $j;
		$temp['txt'] = number_format($j)."개";

		$cart[$i]['deli_option'][] = $temp;
	}
}
$tpl->assign('cart', $cart); 

//배송정보
$delivery = sql_list("select * from rb_delivery_cart as dc left join rb_delivery as d on dc.de_idx = d.de_idx left join rb_order_cart as c on dc.ct_idx = c.ct_idx left join rb_product as p on c.pd_idx = p.pd_idx where dc.od_idx = '$od_idx' order by dc.dt_idx asc");
for($i=0;$i<count($delivery);$i++){
	$_pd_idx = $delivery[$i]['pd_idx'];
	if($_pd_idx){
		$_pd_file_data = sql_fetch("select * from rb_product_file where pd_idx = '$_pd_idx' and fi_num = 0");
		$delivery[$i]['fi_name'] = $_pd_file_data['fi_name'];
		$delivery[$i]['fi_name_org'] = $_pd_file_data['fi_name_org'];
		$delivery[$i]['fi_idx'] = $_pd_file_data['fi_idx'];
	}
}
$tpl->assign('delivery', $delivery); 
//p_arr($delivery);
$tpl->print_('body');
include "../inc/_tail.php";
?> 
