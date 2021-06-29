<?
include "../_inc/_common.php";
header("Content-Type: application/json;charset=utf-8");

$ic_type = $_GET[ic_type];
$pd_idx = $_GET[pd_idx];

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


$chk_icon = sql_fetch("select * from rb_icon where pd_idx = '$pd_idx' and ic_type = '$ic_type'");
if($chk_like[ic_idx]){
	$arr[result] = "error";
	$arr[msg] = "이미 추가된 상품입니다.";
	echo json_encode($arr);exit;
}else{
	sql_query("insert into rb_icon set pd_idx = '$pd_idx', ic_type = '$ic_type', ic_sort = '99999999'");
	make_ranking_write('rb_icon', 'ic_sort', 'ic_sort asc, ic_idx asc', "ic_type = '".$ic_type."'");
	$arr = array();
	$arr[result] = "success";
	$arr[msg] = "추가되었습니다.";
	echo json_encode($arr);exit;
}
?>