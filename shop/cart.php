<?php
$mobile_page_name = "";
include "../inc/_common.php";


$t_menu = 8;
$l_menu = 5;

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'shop/cart.tpl',
	'left'  =>	'inc/mypage_left.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
));
$product_config = $_cfg['product_config'];
$tpl->assign('product_config', $product_config); 

$where_query = ($is_member) ? "and mb_id = '".$member[mb_id]."'" : "and mb_session = '".session_id()."'";
$_data = sql_list("select * from rb_cart where 1 $where_query order by pd_idx desc");

$total_amount = 0;
$data = array();
for($i=0;$i<count($_data);$i++){
	$product = sql_fetch("select * from rb_product where pd_use = 1 and pd_idx = '".$_data[$i][pd_idx]."'");

	$option1 = make_product_option_value($product[pd_option1]);
	$option2 = make_product_option_value($product[pd_option2]);
	$ct_option = explode(":", $_data[$i][ct_option]);
	if((get_txt_from_data($option1, $ct_option[0], 'o_name', 'o_name') || get_txt_from_data($option2, $ct_option[1], 'o_name', 'o_name') || $ct_option[0] == '-') && $product[pd_idx]){
		$_data[$i][ct_option1] = $ct_option[0];
		$_data[$i][ct_option2] = $ct_option[1];

		$_data[$i][ct_option_price] = get_txt_from_data($option1, $ct_option[0], 'o_name', 'o_price') + get_txt_from_data($option2, $ct_option[1], 'o_name', 'o_price');

		$_pd_idx = $product[pd_idx];
		if($_pd_idx){
			$_pd_file_data = sql_fetch("select * from rb_product_file where pd_idx = '$_pd_idx' and fi_num = 0");
			$product[fi_name] = $_pd_file_data[fi_name];
			$product[fi_name_org] = $_pd_file_data[fi_name_org];
			$product[fi_idx] = $_pd_file_data[fi_idx];

		}

		$price = ($product[pd_price] + $_data[$i][ct_option_price]) * $_data[$i][ct_cnt];

		$product[pd_price_coupon] = $price;

		if($_data[$i][cr_idx]){
			$coupon = sql_fetch("select * from rb_coupon_record as cr left join rb_coupon as c on cr.cp_idx = c.cp_idx where cr.mb_id = '$member[mb_id]' and cr.cr_status = '1'  and c.cp_use = '1' and  cr.cr_idx = '".$_data[$i][cr_idx]."'");
			$c_use = check_can_use_coupon($product, $coupon, $_data[$i][ct_cnt], $_data[$i][ct_option_price]);

			if($c_use){
				sql_query("update rb_cart set cr_idx = 0 where ct_idx != '".$_data[$i][ct_idx]."' and cr_idx = '".$_data[$i][cr_idx]."'");
				$_data[$i][cp_title] = $coupon[cp_title];

				$per_halin = ((int)($price * $coupon[cp_percent] / 100) >= $coupon[cp_max_amount]) ? $coupon[cp_max_amount] : (int)($price * $coupon[cp_percent] / 100);
				$product[pd_price_coupon] = ($coupon[cp_type] == '1') ? $price - $coupon[cp_amount] : $price - $per_halin;

				//적용쿠폰정보 
				$product['coupon_info'] = $coupon;
			}else{
				$_data[$i][cp_title] = "";
				unset($coupon);
				sql_query("update rb_cart set cr_idx = 0 where ct_idx = '".$_data[$i][ct_idx]."'");
			}
		}

		$total_amount += $product[pd_price_coupon];

		//배송비
		switch($product[pd_delivery_type2]){
			case "1" : 

				if($product[pd_delivery_type] == '1'){
					$_data[$i][deli_amount] = number_format($_cfg[shop_config][cf_delivery_amount])."원";
				}else{
					$_data[$i][deli_amount] = number_format($_cfg[shop_config][cf_delivery_amount] * ceil($_data[$i][ct_cnt] / $product[pd_delivery_type_cnt]))."원";
				}

				if($_cfg[shop_config][cf_delivery_type2] == '2'){
					$_data[$i][deli_amount] = "무료배송";
				}else if($_cfg[shop_config][cf_delivery_type2] == '4'){
					if($product[pd_price_coupon] >= $_cfg[shop_config][cf_delivery_free_amount]){
						$_data[$i][deli_amount] = "무료배송";
					}
				}

			break;
			case "3" : 
				if($product[pd_delivery_type] == '1'){
					$_data[$i][deli_amount] = number_format($product[pd_delivery_amount])."원";
				}else{
					$_data[$i][deli_amount] = number_format($product[pd_delivery_amount] * ceil($_data[$i][ct_cnt] / $product[pd_delivery_type_cnt]))."원";
				}
			break;
			case "4" : 
				if($product[pd_price_coupon] >= $product[pd_delivery_free_amount]){
					$_data[$i][deli_amount] = "무료배송";
				}else if($product[pd_delivery_type] == '1'){
					$_data[$i][deli_amount] = number_format($product[pd_delivery_amount])."원";
				}else{
					$_data[$i][deli_amount] = number_format($product[pd_delivery_amount] * ceil($_data[$i][ct_cnt] / $product[pd_delivery_type_cnt]))."원";
				}
			break;
			default :
				$_data[$i][deli_amount] = "무료배송";
			break;
		}

		$data[] = array_merge($_data[$i], $product);
	}
}
$tpl->assign('data', $data);
$tpl->assign('total_amount', $total_amount);
//p_arr($data);


include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>