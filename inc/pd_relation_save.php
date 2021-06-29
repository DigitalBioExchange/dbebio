<?
include "../_inc/_common.php";
header("Content-Type: application/json;charset=utf-8");

$pd_idx = $_GET[pd_idx];
$pd_relation = $_GET[pd_relation];

if(!$is_member){
	$arr = array();
	$arr[result] = "error";
	$arr[msg] = "로그인 후 이용하세요";
	echo json_encode($arr);exit;
}

$chk = sql_fetch("select * from rb_product where pd_idx = '{$pd_idx}'");
if(!$chk[pd_idx]){
	$arr = array();
	$arr[result] = "error";
	$arr[msg] = "없는 상품입니다.";
	echo json_encode($arr);exit;
}

sql_query("update rb_product set pd_relation = '$pd_relation' where pd_idx = '{$pd_idx}'");

$arr = array();
$arr[result] = "success";
$arr[msg] = "저장되었습니다.";
echo json_encode($arr);exit;
?>