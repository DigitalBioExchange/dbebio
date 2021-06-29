<?
include "../_inc/_common.php";
header("Content-Type: application/json;charset=utf-8");

$pd_idx = $_POST[pd_idx];

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

sql_query("insert into rb_product_qna set mb_id = '$member[mb_id]', pd_idx = '{$pd_idx}', pq_title = '$_POST[pq_title]', pq_contents = '$_POST[pq_contents]', pq_regdate = now()");

$arr = array();
$arr[result] = "success";
$arr[msg] = "저장되었습니다.";
echo json_encode($arr);exit;
?>