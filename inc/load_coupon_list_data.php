<?
include "../_inc/_common.php";
header("Content-Type: application/json;charset=utf-8");

$ct_idx = $_GET[ct_idx];
$pd_idx = $_GET[pd_idx];
$ct_cnt = $_GET[ct_cnt];
$ct_option_price = $_GET[ct_option_price];

if(!$is_member){
	$arr = array();
	$arr[result] = "error";
	$arr[msg] = "로그인 후 이용하세요";
	echo json_encode($arr);exit;
}

$product = sql_fetch("select * from rb_product where pd_use = 1 and pd_idx = '".$pd_idx."'");
if(!$product[pd_idx]){
	$arr = array();
	$arr[result] = "error";
	$arr[msg] = "없는 상품입니다.";
	echo json_encode($arr);exit;
}

//내 사용가능한 쿠폰 전체
$coupon_list = array();
$_coupon_list = sql_list("select * from rb_coupon_record as cr left join rb_coupon as c on cr.cp_idx = c.cp_idx where cr.mb_id = '$member[mb_id]' and cr.cr_status = '1'  and c.cp_use = '1' order by cr.cr_idx desc");
for($j=0;$j<count($_coupon_list);$j++){
	$c_use = check_can_use_coupon($product, $_coupon_list[$j], $ct_cnt, $ct_option_price);
	if($c_use){
		$coupon = $_coupon_list[$j];
		
		$per_halin = ((int)((($product[pd_price] + $ct_option_price) * $ct_cnt) * $coupon[cp_percent] / 100) >= $coupon[cp_max_amount]) ? $coupon[cp_max_amount] : (int)((($product[pd_price] + $ct_option_price) * $ct_cnt) * $coupon[cp_percent] / 100);
		//$c_price = ($coupon[cp_type] == '1') ? ($product[pd_price] + $ct_option_price) - $coupon[cp_amount] : ($product[pd_price] + $ct_option_price) - $per_halin;

		$_coupon_list[$j][c_price] = ($_coupon_list[$j][cp_type] == '1') ? $_coupon_list[$j][cp_amount] : $per_halin;
		$coupon_list[] = $_coupon_list[$j];
	}
}

$arr = array();
$arr[result] = "success";
$arr[datas] = $coupon_list;
$arr[datas_cnt] = count($coupon_list);
echo json_encode($arr);exit;
?>