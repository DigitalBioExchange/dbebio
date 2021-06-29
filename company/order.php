<?php
$page_name = "";
$page_option = "header-white";
include "../inc/_common.php";

if ($user_agent != "app") {
	$gnb = "4";
	goto_login();
}

sql_query("delete from rb_cart_temp where ct_regdate < DATE_ADD(now(), interval -2 day)");
$t_menu = 0;
$l_menu = 0;

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'company/order.tpl',
	'left'  =>	'inc/member_left.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
));
$product_config = $_cfg['product_config'];
$tpl->assign('product_config', $product_config); 

$where_query = ($is_member) ? "and mb_id = '".$member['mb_id']."'" : "and mb_session = '".session_id()."'";
if($_GET['mode'] == "all"){
	$_data = sql_list("select * from rb_cart where 1 $where_query order by pd_idx desc");
	$tpl->assign('data', $data);
}else if($_GET['mode'] == "select"){
	$ct_num = substr($_GET['ct_num'], (0 - substr($_GET['ct_num'], 0, 1)));
	$_data = sql_list("select ct_idx, pd_idx, ct_cnt, ct_option, cr_idx from rb_cart_temp where ct_num = '$ct_num' $where_query order by pd_idx desc");
}else if($_GET['mode'] == "direct"){
	$ct_num = substr($_GET['ct_num'], (0 - substr($_GET['ct_num'], 0, 1)));
	$_data = sql_list("select ctp_idx as ct_idx, pd_idx, ct_cnt, ct_option, cr_idx from rb_cart_temp where ct_num = '$ct_num' $where_query order by pd_idx desc");
}else{
	alert("잘못된 접근입니다.");
}
$tpl->assign('mode', $_GET['mode']);
//p_arr($_data);exit;

$total_amount = 0;
$data = array();
//묶음 && 무료배송이 되는 것이 하나라도 있는지 검사
$group_delivery_amount = array();

for($i=0;$i<count($_data);$i++){
	$product = sql_fetch("select * from rb_product where pd_use = 1 and pd_idx = '".$_data[$i]['pd_idx']."'");

	$option1 = make_product_option_value($product['pd_option1']);
	$option2 = make_product_option_value($product['pd_option2']);
	$ct_option = explode(":", $_data[$i]['ct_option']);
	if((get_txt_from_data($option1, $ct_option[0], 'o_name', 'o_name') || get_txt_from_data($option2, $ct_option[1], 'o_name', 'o_name') || $ct_option[0] == '-') && $product['pd_idx']){
		$_data[$i]['ct_option1'] = $ct_option[0];
		$_data[$i]['ct_option2'] = $ct_option[1];

		$_data[$i]['ct_option1_price'] = get_txt_from_data($option1, $ct_option[0], 'o_name', 'o_price');
		$_data[$i]['ct_option2_price'] = get_txt_from_data($option2, $ct_option[1], 'o_name', 'o_price');

		$_data[$i]['ct_option_price'] = get_txt_from_data($option1, $ct_option[0], 'o_name', 'o_price') + get_txt_from_data($option2, $ct_option[1], 'o_name', 'o_price');

		$_pd_idx = $product['pd_idx'];
		if($_pd_idx){
			$_pd_file_data = sql_fetch("select * from rb_product_file where pd_idx = '$_pd_idx' and fi_num = 0");
			$product['fi_name'] = $_pd_file_data['fi_name'];
			$product['fi_name_org'] = $_pd_file_data['fi_name_org'];
			$product['fi_idx'] = $_pd_file_data['fi_idx'];

		}

		//다국어라서 언어표현하기위한 공통변수작업
		$product['pd_name'] = $product['pd_name_'.$lang_code];
		$product['pd_exp'] = $product['pd_exp_'.$lang_code];
		$product['pd_contents'] = $product['pd_contents_'.$lang_code];
		$product['pd_delivery_contents'] = $product['pd_delivery_contents_'.$lang_code];

		$price_2 = ($product['pd_price2'] + $_data[$i]['ct_option_price']) * $_data[$i]['ct_cnt']; //할인전가격
		$price = ($product['pd_price'] + $_data[$i]['ct_option_price']) * $_data[$i]['ct_cnt'];

		$product['pd_price_coupon'] = $price;
		$product['basic_price'] = $price ;

		if($_data[$i]['cr_idx']){
			$coupon = sql_fetch("select * from rb_coupon_record as cr left join rb_coupon as c on cr.cp_idx = c.cp_idx where cr.mb_id = '".$member['mb_id']."' and cr.cr_status = '1'  and c.cp_use = '1' and  cr.cr_idx = '".$_data[$i]['cr_idx']."'");
			$c_use = check_can_use_coupon($product, $coupon, $_data[$i]['ct_cnt'], $_data[$i]['ct_option_price']);

			if($c_use){
				sql_query("update rb_cart set cr_idx = 0 where ct_idx != '".$_data[$i]['ct_idx']."' and cr_idx = '".$_data[$i]['cr_idx']."'");
				$_data[$i]['cp_title'] = $coupon['cp_title'];

				$per_halin = ((float)($price * $coupon['cp_percent'] / 100) >= $coupon['cp_max_amount']) ? $coupon['cp_max_amount'] : (float)($price * $coupon['cp_percent'] / 100);
				$product['pd_price_coupon'] = ($coupon['cp_type'] == '1') ? $price - $coupon['cp_amount'] : $price - $per_halin;

				//적용쿠폰정보 
				$product['coupon_info'] = $coupon;
			}else{
				$_data[$i]['cp_title'] = "";
				unset($coupon);
				sql_query("update rb_cart set cr_idx = 0 where ct_idx = '".$_data[$i]['ct_idx']."'");
			}
		}


		$total_amount += $product['pd_price_coupon'];

		//배송비
		switch($product['pd_delivery_type2']){
			case "1" : 

				if($product['pd_delivery_type'] == '1'){
					$_data[$i]['deli_amount'] = number_format($_cfg['shop_config']['cf_delivery_amount'])."원";
					$_data[$i]['pd_deli_amount'] = $_cfg['shop_config']['cf_delivery_amount'];
				}else{
					$_data[$i]['deli_amount'] = number_format($_cfg['shop_config']['cf_delivery_amount'] * ceil($_data[$i]['ct_cnt'] / $product['pd_delivery_type_cnt']))."원";
					$_data[$i]['pd_deli_amount'] = $_cfg['shop_config']['cf_delivery_amount'] * ceil($_data[$i]['ct_cnt'] / $product['pd_delivery_type_cnt']);
				}

				if($_cfg['shop_config']['cf_delivery_type2'] == '2'){
					$_data[$i]['deli_amount'] = "무료배송";
					$_data[$i]['pd_deli_amount'] = 0;
				}else if($_cfg['shop_config']['cf_delivery_type2'] == '4'){
					if($product['pd_price_coupon'] >= $_cfg['shop_config']['cf_delivery_free_amount']){
						$_data[$i]['deli_amount'] = "무료배송";
						$_data[$i]['pd_deli_amount'] = 0;
					}
				}

			break;
			case "3" : 
				if($product['pd_delivery_type'] == '1'){
					$_data[$i]['deli_amount'] = number_format($product['pd_delivery_amount'])."원";
					$_data[$i]['pd_deli_amount'] = $product['pd_delivery_amount'];
				}else{
					$_data[$i]['deli_amount'] = number_format($product['pd_delivery_amount'] * ceil($_data[$i]['ct_cnt'] / $product['pd_delivery_type_cnt']))."원";
					$_data[$i]['pd_deli_amount'] = $product['pd_delivery_amount'] * ceil($_data[$i]['ct_cnt'] / $product['pd_delivery_type_cnt']);
				}
			break;
			case "4" : 
				if($product['pd_price_coupon'] >= $product['pd_delivery_free_amount']){
					$_data[$i]['deli_amount'] = "무료배송";
					$_data[$i]['pd_deli_amount'] = 0;
				}else if($product['pd_delivery_type'] == '1'){
					$_data[$i]['deli_amount'] = number_format($product['pd_delivery_amount'])."원";
					$_data[$i]['pd_deli_amount'] = $product['pd_delivery_amount'];
				}else{
					$_data[$i]['deli_amount'] = number_format($product['pd_delivery_amount'] * ceil($_data[$i]['ct_cnt'] / $product['pd_delivery_type_cnt']))."원";
					$_data[$i]['pd_deli_amount'] = $product['pd_delivery_amount'] * ceil($_data[$i]['ct_cnt'] / $product['pd_delivery_type_cnt']);
				}
			break;
			default :
				$_data[$i]['deli_amount'] = "무료배송";
				$_data[$i]['pd_deli_amount'] = 0;
			break;
		}

		if($product['pd_delivery_type'] == '1'){
			if($_data[$i]['deli_amount'] == "무료배송"){
				$group_delivery_amount[] = 0;
			}else if($_data[$i]['deli_amount'] != "무료배송"){
				$group_delivery_amount[] = $_data[$i]['pd_deli_amount'];
			}
		}

		$pd_price2_arr = explode('.', $price_2);
		if (isset($pd_price2_arr[1])) {
			if (strlen($pd_price2_arr[1]) == 1) {
				$pd_price2_arr[1] = $pd_price2_arr[1].'0';
			}
			$pd_price2_text = number_format($pd_price2_arr[0]).'.'.$pd_price2_arr[1];
		} else {
			$pd_price2_text = number_format($pd_price2_arr[0]).'.00';
		}
		$product['pd_price2_text'] = $pd_price2_text;

		$pd_price_arr = explode('.', $price);
		if (isset($pd_price_arr[1])) {
			if (strlen($pd_price_arr[1]) == 1) {
				$pd_price_arr[1] = $pd_price_arr[1].'0';
			}
			$pd_price_text = number_format($pd_price_arr[0]).'.'.$pd_price_arr[1];
		} else {
			$pd_price_text = number_format($pd_price_arr[0]).'.00';
		}
		$product['pd_price_text'] = $pd_price_text;


		//echo $_data[$i][pd_deli_amount]."|".$_data[$i][deli_amount]."<br>";

		//$total_delivery_amount += $product[pd_price_coupon];

		$data[] = array_merge($_data[$i], $product);
	}
}

if(count($data) == 0){
	alert("구매할 상품이 없습니다.");
}

$can_group_amount = (count($group_delivery_amount) > 0) ? min($group_delivery_amount) : 0;

$deli_chk = 0;

$total_amount_all = 0;
$total_amount_halin = 0;

for($i=0;$i<count($data);$i++){
	if($data[$i]['pd_delivery_type'] == '1'){
		if($data[$i]['deli_amount'] != "무료배송"){
			if($can_group_amount != "" && $data[$i]['pd_deli_amount'] == $can_group_amount){
				if($deli_chk != 0){
					$data[$i]['deli_amount'] = "무료배송";
					$data[$i]['pd_deli_amount'] = 0;
				}else{
					$deli_chk = 1;
				}
			}else{
				$data[$i]['deli_amount'] = "무료배송";
				$data[$i]['pd_deli_amount'] = 0;
			}
		}
	}else{
		$total_delivery_amount += $data[$i]['pd_deli_amount'];
	}

	$total_amount_all += $data[$i]['basic_price'];
	$total_amount_halin += $data[$i]['coupon_price'];
}
$total_delivery_amount += $can_group_amount;


//금액표기 재정의
$total_amount_arr = explode('.', $total_amount);
if (isset($total_amount_arr[1])) {
	if (strlen($total_amount_arr[1]) == 1) {
		$total_amount_arr[1] = $total_amount_arr[1].'0';
	}
	$total_amount_text = number_format($total_amount_arr[0]).'.'.$total_amount_arr[1];
} else {
	$total_amount_text = number_format($total_amount_arr[0]).'.00';
}


$tpl->assign('data', $data);
$tpl->assign('total_amount', $total_amount + 0);
$tpl->assign('total_amount_text', $total_amount_text);
$tpl->assign('total_amount_all', $total_amount_all + 0);
$tpl->assign('total_amount_halin', $total_amount_halin + 0);
$tpl->assign('total_delivery_amount', $total_delivery_amount + 0);

$tpl->assign('total_pay_amount', $total_amount + $total_delivery_amount);
//p_arr($data);

// $_cfg['is_pg'] = 1;

$od_num = date("YmdHis").substr(md5(uniqid(rand(), TRUE)), 0, 8);
$tpl->assign('od_num', $od_num);

//주소록
$address = sql_list("select * from rb_address where mb_id = '".$member['mb_id']."' order by ad_idx desc");
$tpl->assign('address', $address);

//국가번호
$sql = "select * from rb_country_tel_light";
$data_tel = sql_list($sql);
$tpl->assign('data_tel', $data_tel);

//코인시세 표기
$sql = "select * from rb_coin_price where 1 order by cp_idx desc limit 0, 1";
$data_coin = sql_fetch($sql);
$_price = explode('.', $data_coin['cp_price']);
$data_coin['cp_price_text'] = number_format($_price[0]).".".$_price[1];
$tpl->assign('data_coin', $data_coin);


include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>