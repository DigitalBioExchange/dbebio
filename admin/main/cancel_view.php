<?php
$menu_code = "500100";
$menu_mode = "v";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";



$tpl=new Template;
$tpl->define(array(
    'contents'  =>'main/cancel_view.tpl',
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



$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

$data = sql_fetch("select *, (select mb_coin_address from rb_member where mb_id = b.mb_id) as mb_coin_address from rb_board as b where b.bd_idx = '$bd_idx' $search_query");
$tpl->assign('data', $data);
if(!$data['bd_idx']) alert("없는 신청건입니다.");

$sql_od = "select * from rb_order where od_idx = '".$data['od_idx']."' ";
$data_od = sql_fetch($sql_od);
if ($data_od['od_idx']) {
	$sql_cart = "select * from rb_order_cart as ct 
								left join rb_product as pd on pd.pd_idx = ct.pd_idx 
								where od_idx = '".$data_od['od_idx']."' ";
	$data_cart = sql_list($sql_cart);
	foreach ($data_cart as $key => $value) {

		$product = sql_fetch("select * from rb_product where pd_use = 1 and pd_idx = '".$value['pd_idx']."'");

		$option1 = make_product_option_value($product['pd_option1']);
		$option2 = make_product_option_value($product['pd_option2']);

		$ct_option = explode(":", $value['ct_option']);
		if((get_txt_from_data($option1, $ct_option[0], 'o_name', 'o_name') || get_txt_from_data($option2, $ct_option[1], 'o_name', 'o_name') || $ct_option[0] == '-') && $product['pd_idx']){
			$data_cart[$key]['ct_option1'] = $ct_option[0];
			$data_cart[$key]['ct_option2'] = $ct_option[1];

			$data_cart[$key]['ct_option_price'] = get_txt_from_data($option1, $ct_option[0], 'o_name', 'o_price') + get_txt_from_data($option2, $ct_option[1], 'o_name', 'o_price');
			
			//다국어라서 언어표현하기위한 공통변수작업
			// $product['pd_name'] = $product['pd_name_'.$lang_code];
			// $product['pd_exp'] = $product['pd_exp_'.$lang_code];
			// $product['pd_contents'] = $product['pd_contents_'.$lang_code];
			// $product['pd_delivery_contents'] = $product['pd_delivery_contents_'.$lang_code];

			$data_cart[$key]['price_2'] = ($product['pd_price2'] + $data_cart[$key]['ct_option_price']) * $value['ct_cnt']; //할인전가격
			$data_cart[$key]['price'] = ($product['pd_price'] + $data_cart[$key]['ct_option_price']) * $value['ct_cnt']; //할인후가격
			// $product_t_cnt = $product_t_cnt + $value['ct_cnt'];
		}
		$_pd_idx = $product['pd_idx'];
		if($_pd_idx){
			$_pd_file_data = sql_fetch("select * from rb_product_file where pd_idx = '$_pd_idx' and fi_num = 0");
			$data_cart[$key]['fi_name'] = $_pd_file_data['fi_name'];
			$data_cart[$key]['fi_name_org'] = $_pd_file_data['fi_name_org'];
			$data_cart[$key]['fi_idx'] = $_pd_file_data['fi_idx'];
		}

	}

	$total_pay_amount_arr = explode('.', $data_od['total_pay_amount']);
	if (isset($total_pay_amount_arr[1])) {
		if (strlen($total_pay_amount_arr[1]) == 1) {
			$total_pay_amount_arr[1] = $total_pay_amount_arr[1].'000';
		}
		$total_pay_amount_text = number_format($total_pay_amount_arr[0]).'.'.$total_pay_amount_arr[1];
	} else {
		$total_pay_amount_text = number_format($total_pay_amount_arr[0]).'.0000';
	}
	$data_od['total_pay_amount_text'] = $total_pay_amount_text;
	$data_od['od_cart'] = $data_cart;
}
$tpl->assign('data_od', $data_od);
// p_arr($option1);exit;

$bd_file_data = sql_list("select * from rb_board_file where bd_idx = '$bd_idx' order by fi_num asc");
$tpl->assign('bd_file_data', $bd_file_data);

$tpl->print_('body');
include "../inc/_tail.php";
?> 
