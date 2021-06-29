<?
include "../_inc/_common.php";
header("Content-Type: application/json;charset=utf-8");

$c2_idx = $_GET[c2_idx];

$chk = sql_fetch("select * from rb_cate2 as c2 where c2.c2_idx = '$c2_idx' ");
if(!$chk[c1_idx]){
	$arr = array();
	$arr[result] = "error";
	$arr[msg] = "없는 카테고리입니다.";
	echo json_encode($arr);exit;
}

$sql = "select * from rb_cate3 as c3 where c2_idx = '$c2_idx' order by c3.c3_sort asc";
$data = sql_list($sql);

$arr = array();
$arr[result] = "success";
$arr[datas] = $data;
$arr[datas_cnt] = count($data);
echo json_encode($arr);exit;
?>