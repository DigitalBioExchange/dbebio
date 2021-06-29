<?
include "../_inc/_common.php";
header("Content-Type: application/json;charset=utf-8");


if($_POST[mode] == "select"){
	$ct_idx_arr = array();
}
$od_title = "";
$od_get_point = 0;
for($i=0;$i<$_POST[total_cnt];$i++){
	$ct_idx = $_POST["ct_idx_".$i];
	$pd_idx = $_POST["pd_idx"."_".$ct_idx];

	$ct_cnt = $_POST["ct_cnt"."_".$ct_idx];	//갯수

	$ct_option1 = $_POST["ct_option1"."_".$ct_idx];	//옵션1
	$ct_option2 = $_POST["ct_option2"."_".$ct_idx];	//옵션2

	$ct_option1_price = $_POST["ct_option1_price"."_".$ct_idx];	//옵션1
	$ct_option2_price = $_POST["ct_option2_price"."_".$ct_idx];	//옵션2

	$ct_price = $_POST["pd_price"."_".$ct_idx];	//단가
	$ct_price2 = $_POST["pd_price2"."_".$ct_idx];	//할인전단가

	$ct_option_price = $_POST["ct_option_price"."_".$ct_idx];	//옵션변동가
	$ct_basic_price = $_POST["basic_price"."_".$ct_idx];	// (단가 + 옵션변동가) * 갯수

	$cr_idx = $_POST["cr_idx"."_".$ct_idx];
	$ct_coupon_unit_price = $_POST["coupon_unit_price"."_".$ct_idx];
	$ct_coupon_price = $_POST["coupon_price"."_".$ct_idx];

	$ct_deli_amount = $_POST["pd_deli_amount"."_".$ct_idx];
	$ct_amount = $_POST["pd_price_coupon"."_".$ct_idx];
	if($_POST[mode] == "select"){
		$ct_idx_arr[] = $ct_idx;
	}

	//상품검증
	$product = sql_fetch("select * from rb_product where pd_use = 1 and pd_idx = '".$pd_idx."'");

	$option1 = make_product_option_value($product[pd_option1]);
	$option2 = make_product_option_value($product[pd_option2]);

	if($ct_option1 != ""){
		if((!get_txt_from_data($option1, $ct_option1, 'o_name', 'o_name') && $ct_option1 != "-") || get_txt_from_data($option1, $ct_option1, 'o_name', 'o_price') != $ct_option1_price){
			ajax_error_print("잘못된 접근입니다.");
		}
	}else{
		if($product[pd_option1] != ""){
			ajax_error_print("잘못된 접근입니다.");
		}
	}

	if($ct_option2 != ""){
		if((!get_txt_from_data($option2, $ct_option2, 'o_name', 'o_name') && $ct_option2 != "-") || get_txt_from_data($option2, $ct_option2, 'o_name', 'o_price') != $ct_option2_price){
			ajax_error_print("잘못된 접근입니다.");
		}
	}else{
		if($product[pd_option2] != ""){
			ajax_error_print("잘못된 접근입니다.");
		}
	}

	if($od_title == ""){
		$od_title = $product[pd_name];
		if($_POST[total_cnt] > 1){
			$od_title .= "외 ".number_format($_POST[total_cnt] - 1)."건";
		}
	}

	$od_get_point += $product[pd_point] * $ct_cnt;

	//쿠폰확인
	if($cr_idx){
		$coupon = sql_fetch("select * from rb_coupon_record as cr left join rb_coupon as c on cr.cp_idx = c.cp_idx where cr.mb_id = '$member[mb_id]' and cr.cr_status = '1'  and c.cp_use = '1' and  cr.cr_idx = '".$cr_idx."'");
		if(!$coupon[cr_idx]){
			ajax_error_print("잘못된 접근입니다.");
		}
	}
}


$sql_common = "
	mb_id = '".$member[mb_id]."',
	od_num = '".$_POST[od_num]."',
	od_title = '$od_title',
	od_paymethod = '".$_POST[od_paymethod]."',
	od_ipgum_bank = '".$_POST[od_ipgum_bank]."',
	od_ipgum_name = '".$_POST[od_ipgum_name]."',

	od_status = '0',

	od_pass = '".$_POST[od_pass]."',

	ad_idx = '".$_POST[ad_idx]."',

	od_name = '".$_POST[od_name]."',
	od_tel = '".$_POST[od_tel]."',
	od_zip = '".$_POST[od_zip]."',
	od_addr1 = '".$_POST[od_addr1]."',
	od_addr2 = '".$_POST[od_addr2]."',
	od_msg = '".$_POST[od_msg]."',

	de_name = '".$_POST[de_name]."',
	de_tel = '".$_POST[de_tel]."',
	de_zip = '".$_POST[de_zip]."',
	de_addr1 = '".$_POST[de_addr1]."',
	de_addr2 = '".$_POST[de_addr2]."',

	total_amount_all = '".$_POST[total_amount_all]."',
	total_amount_halin = '".$_POST[total_amount_halin]."',
	total_amount = '".$_POST[total_amount]."',
	total_delivery_amount = '".$_POST[total_delivery_amount]."',
	od_point = '".$_POST[od_point]."',
	total_pay_amount = '".$_POST[total_pay_amount]."',
	
	od_get_point = '".$od_get_point."'
";


$sql = "insert into rb_order set
			$sql_common,
			od_regdate = now()
		";


$sql_q = sql_query($sql);
$od_idx = mysql_insert_id();

/*
if($is_member && $_POST[od_point] > 0){
	write_member_point($member[mb_id], (0 - $_POST[od_point]), $od_title." 구매" );
}
*/

//새배송지등록
if($is_member && $_POST[use_address] == 0){
	sql_query("
	insert into rb_address set
		mb_id = '".$member[mb_id]."',
		ad_name = '".$_POST[de_name]."',
		ad_tel = '".$_POST[de_tel]."',
		ad_zip = '".$_POST[de_zip]."',
		ad_addr1 = '".$_POST[de_addr1]."',
		ad_addr2 = '".$_POST[de_addr2]."'
	");
}

//상품카트등록
for($i=0;$i<$_POST[total_cnt];$i++){

	$ct_idx = $_POST["ct_idx_".$i];
	$pd_idx = $_POST["pd_idx"."_".$ct_idx];

	$ct_cnt = $_POST["ct_cnt"."_".$ct_idx];	//갯수

	$ct_option1 = $_POST["ct_option1"."_".$ct_idx];	//옵션1
	$ct_option2 = $_POST["ct_option2"."_".$ct_idx];	//옵션2

	$ct_option1_price = $_POST["ct_option1_price"."_".$ct_idx];	//옵션1
	$ct_option2_price = $_POST["ct_option2_price"."_".$ct_idx];	//옵션2

	$ct_price = $_POST["pd_price"."_".$ct_idx];	//단가
	$ct_price2 = $_POST["pd_price2"."_".$ct_idx];	//할인전단가

	$ct_option_price = $_POST["ct_option_price"."_".$ct_idx];	//옵션변동가
	$ct_basic_price = $_POST["basic_price"."_".$ct_idx];	// (단가 + 옵션변동가) * 갯수

	$cr_idx = $_POST["cr_idx"."_".$ct_idx];
	$cp_title = $_POST["cp_title"."_".$ct_idx];
	$ct_coupon_unit_price = $_POST["coupon_unit_price"."_".$ct_idx];
	$ct_coupon_price = $_POST["coupon_price"."_".$ct_idx];

	$ct_deli_amount = $_POST["pd_deli_amount"."_".$ct_idx];
	$ct_amount = $_POST["pd_price_coupon"."_".$ct_idx];

	sql_query("
	insert into rb_order_cart set
		mb_id = '".$member[mb_id]."',
		ct_status = '0',
		od_idx = '".$od_idx."',
		pd_idx = '".$pd_idx."',
		ct_option1 = '".$ct_option1."',
		ct_option2 = '".$ct_option2."',
		ct_option1_price = '".$ct_option1_price."',
		ct_option2_price = '".$ct_option2_price."',
		ct_cnt = '".$ct_cnt."',
		ct_price = '".$ct_price."',
		ct_price2 = '".$ct_price2."',
		ct_option_price = '".$ct_option_price."',
		ct_basic_price = '".$ct_basic_price."',
		cr_idx = '".$cr_idx."',
		cp_title = '".$cp_title."',
		ct_coupon_unit_price = '".$ct_coupon_unit_price."',
		ct_coupon_price = '".$ct_coupon_price."',
		ct_deli_amount = '".$ct_deli_amount."',
		ct_amount = '".$ct_amount."'
	");

	//쿠폰사용처리
	/*
	if($cr_idx){
		sql_query("update rb_coupon_record set cr_status = 2, cr_use_date = now() where cr_idx = '$cr_idx'");
	}
	*/
}


//장바구니처리
$where_query = ($is_member) ? "and mb_id = '".$member[mb_id]."'" : "and mb_session = '".session_id()."'";
if($_POST[mode] == "all"){
	sql_query("delete from rb_cart where 1 $where_query");
}else if($_POST[mode] == "select"){
	sql_query("delete from rb_cart where ct_idx in (".implode(",", $ct_idx_arr).") $where_query");
}else if($_POST[mode] == "direct"){
	sql_query("delete from rb_cart_temp where 1 $where_query");
}

$arr = array();
$arr[result] = "success";
$arr[msg] = "";
print json_encode($arr);exit;
?>