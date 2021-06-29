<?
include "../_inc/_common.php";
header("Content-Type: application/json;charset=utf-8");

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


$chk_scrap = sql_fetch("select * from rb_product_scrap where pd_idx = '$pd_idx' and mb_id = '$member[mb_id]'");
if($chk_scrap[sc_idx]){

	$arr = array();
	$arr[result] = "error";
	$arr[msg] = "이미 스크랩한 상품입니다.";
	echo json_encode($arr);exit;
}else{
	sql_query("insert into rb_product_scrap set pd_idx = '$pd_idx', mb_id = '$member[mb_id]', sc_regdate = now()");
	$scrap_cnt = sql_total("select * from rb_product_scrap where pd_idx = '$pd_idx'");
	sql_query("update rb_product set pd_scrap_cnt = $scrap_cnt where pd_idx = '{$pd_idx}'");
	$arr = array();
	$arr[result] = "success";
	$arr[scrap_result] = "1";
	$arr[scrap_cnt] = $scrap_cnt;
	$arr[msg] = "스크랩되었습니다.";
	echo json_encode($arr);exit;
}
?>