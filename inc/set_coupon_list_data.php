<?
include "../_inc/_common.php";
header("Content-Type: application/json;charset=utf-8");

$ct_idx = $_GET[ct_idx];
$new_cr_idx = $_GET[new_cr_idx];
$in_exists = $_GET[in_exists];

if(!$is_member){
	$arr = array();
	$arr[result] = "error";
	$arr[msg] = "로그인 후 이용하세요";
	echo json_encode($arr);exit;
}

sql_query("update rb_cart set cr_idx = '$new_cr_idx' where ct_idx = '$ct_idx'");
sql_query("update rb_cart set cr_idx = '0' where ct_idx = '".$in_exists."'");

$coupon = sql_fetch("select * from rb_coupon_record as cr left join rb_coupon as c on cr.cp_idx = c.cp_idx where cr.mb_id = '$member[mb_id]' and cr.cr_status = '1'  and c.cp_use = '1' and  cr.cr_idx = '".$new_cr_idx."'");

$arr = array();
$arr[result] = "success";
$arr[datas] = $coupon;
echo json_encode($arr);exit;
?>