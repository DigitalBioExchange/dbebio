<?php
$page_name = "not-aside";
$mobile_page_name = "";
include "../inc/_common.php";

sql_query("delete from rb_cart_temp where ct_regdate < DATE_ADD(now(), interval -2 day)");
$t_menu = 0;
$l_menu = 0;

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'shop/order.tpl',
	'left'  =>	'inc/mypage_left.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
));
$product_config = $_cfg['product_config'];
$tpl->assign('product_config', $product_config); 

$where_query = ($is_member) ? "and mb_id = '".$member[mb_id]."'" : "and mb_session = '".session_id()."'";
if($_GET[mode] == "all"){
	$_data = sql_list("select * from rb_cart where 1 $where_query order by pd_idx desc");
	$tpl->assign('data', $data);
}else if($_GET[mode] == "select"){
	$ct_num = substr($_GET[ct_num], (0 - substr($_GET[ct_num], 0, 1)));
	$_data = sql_list("select ct_idx, pd_idx, ct_cnt, ct_option, cr_idx from rb_cart_temp where ct_num = '$ct_num' $where_query order by pd_idx desc");
}else if($_GET[mode] == "direct"){
	$ct_num = substr($_GET[ct_num], (0 - substr($_GET[ct_num], 0, 1)));
	$_data = sql_list("select ctp_idx as ct_idx, pd_idx, ct_cnt, ct_option, cr_idx from rb_cart_temp where ct_num = '$ct_num' $where_query order by pd_idx desc");
}else{
	alert("잘못된 접근입니다.");
}
$tpl->assign('mode', $_GET[mode]);
//p_arr($_data);exit;

$total_amount = 0;
$data = array();
//묶음 && 무료배송이 되는 것이 하나라도 있는지 검사
$group_delivery_amount = array();

for($i=0;$i<count($_data);$i++){
	$product = sql_fetch("select * from rb_product where pd_use = 1 and pd_idx = '".$_data[$i][pd_idx]."'");

	$option1 = make_product_option_value($product[pd_option1]);
	$option2 = make_product_option_value($product[pd_option2]);
	$ct_option = explode(":", $_data[$i][ct_option]);
	if((get_txt_from_data($option1, $ct_option[0], 'o_name', 'o_name') || get_txt_from_data($option2, $ct_option[1], 'o_name', 'o_name') || $ct_option[0] == '-') && $product[pd_idx]){
		$_data[$i][ct_option1] = $ct_option[0];
		$_data[$i][ct_option2] = $ct_option[1];

		$_data[$i][ct_option1_price] = get_txt_from_data($option1, $ct_option[0], 'o_name', 'o_price');
		$_data[$i][ct_option2_price] = get_txt_from_data($option2, $ct_option[1], 'o_name', 'o_price');

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
		$product[basic_price] = $price ;

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
					$_data[$i][pd_deli_amount] = $_cfg[shop_config][cf_delivery_amount];
				}else{
					$_data[$i][deli_amount] = number_format($_cfg[shop_config][cf_delivery_amount] * ceil($_data[$i][ct_cnt] / $product[pd_delivery_type_cnt]))."원";
					$_data[$i][pd_deli_amount] = $_cfg[shop_config][cf_delivery_amount] * ceil($_data[$i][ct_cnt] / $product[pd_delivery_type_cnt]);
				}

				if($_cfg[shop_config][cf_delivery_type2] == '2'){
					$_data[$i][deli_amount] = "무료배송";
					$_data[$i][pd_deli_amount] = 0;
				}else if($_cfg[shop_config][cf_delivery_type2] == '4'){
					if($product[pd_price_coupon] >= $_cfg[shop_config][cf_delivery_free_amount]){
						$_data[$i][deli_amount] = "무료배송";
						$_data[$i][pd_deli_amount] = 0;
					}
				}

			break;
			case "3" : 
				if($product[pd_delivery_type] == '1'){
					$_data[$i][deli_amount] = number_format($product[pd_delivery_amount])."원";
					$_data[$i][pd_deli_amount] = $product[pd_delivery_amount];
				}else{
					$_data[$i][deli_amount] = number_format($product[pd_delivery_amount] * ceil($_data[$i][ct_cnt] / $product[pd_delivery_type_cnt]))."원";
					$_data[$i][pd_deli_amount] = $product[pd_delivery_amount] * ceil($_data[$i][ct_cnt] / $product[pd_delivery_type_cnt]);
				}
			break;
			case "4" : 
				if($product[pd_price_coupon] >= $product[pd_delivery_free_amount]){
					$_data[$i][deli_amount] = "무료배송";
					$_data[$i][pd_deli_amount] = 0;
				}else if($product[pd_delivery_type] == '1'){
					$_data[$i][deli_amount] = number_format($product[pd_delivery_amount])."원";
					$_data[$i][pd_deli_amount] = $product[pd_delivery_amount];
				}else{
					$_data[$i][deli_amount] = number_format($product[pd_delivery_amount] * ceil($_data[$i][ct_cnt] / $product[pd_delivery_type_cnt]))."원";
					$_data[$i][pd_deli_amount] = $product[pd_delivery_amount] * ceil($_data[$i][ct_cnt] / $product[pd_delivery_type_cnt]);
				}
			break;
			default :
				$_data[$i][deli_amount] = "무료배송";
				$_data[$i][pd_deli_amount] = 0;
			break;
		}

		if($product[pd_delivery_type] == '1'){
			if($_data[$i][deli_amount] == "무료배송"){
				$group_delivery_amount[] = 0;
			}else if($_data[$i][deli_amount] != "무료배송"){
				$group_delivery_amount[] = $_data[$i][pd_deli_amount];
			}
		}


		//echo $_data[$i][pd_deli_amount]."|".$_data[$i][deli_amount]."<br>";

		//$total_delivery_amount += $product[pd_price_coupon];

		$data[] = array_merge($_data[$i], $product);
	}
}

if(count($data) == 0){
	alert("구매할 상품이 없습니다.");
}

$can_group_amount = (count($group_delivery_amount) > 0) ? min($group_delivery_amount) : "";

$deli_chk = 0;

$total_amount_all = 0;
$total_amount_halin = 0;

for($i=0;$i<count($data);$i++){
	if($data[$i][pd_delivery_type] == '1'){
		if($data[$i][deli_amount] != "무료배송"){
			if($can_group_amount != "" && $data[$i][pd_deli_amount] == $can_group_amount){
				if($deli_chk != 0){
					$data[$i][deli_amount] = "무료배송";
					$data[$i][pd_deli_amount] = 0;
				}else{
					$deli_chk = 1;
				}
			}else{
				$data[$i][deli_amount] = "무료배송";
				$data[$i][pd_deli_amount] = 0;
			}
		}
	}else{
		$total_delivery_amount += $data[$i][pd_deli_amount];
	}

	$total_amount_all += $data[$i][basic_price];
	$total_amount_halin += $data[$i][coupon_price];
}
$total_delivery_amount += $can_group_amount;

$tpl->assign('data', $data);
$tpl->assign('total_amount', $total_amount + 0);
$tpl->assign('total_amount_all', $total_amount_all + 0);
$tpl->assign('total_amount_halin', $total_amount_halin + 0);
$tpl->assign('total_delivery_amount', $total_delivery_amount + 0);

$tpl->assign('total_pay_amount', $total_amount + $total_delivery_amount);
//p_arr($data);

$_cfg[is_pg] = 1;

$od_num = date("YmdHis").substr(md5(uniqid(rand(), TRUE)), 0, 20);
$tpl->assign('od_num', $od_num);

//주소록
$address = sql_list("select * from rb_address where mb_id = '".$member[mb_id]."' order by ad_idx desc");
$tpl->assign('address', $address);

include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>