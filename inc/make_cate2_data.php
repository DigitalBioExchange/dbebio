<?
include "../_inc/_common.php";
header("Content-Type: application/json;charset=utf-8");

$c1_idx = $_GET[c1_idx];

$chk = sql_fetch("select * from rb_cate1 as c1 where c1.c1_idx = '$c1_idx' ");
if(!$chk[c1_idx]){
	$arr = array();
	$arr[result] = "error";
	$arr[msg] = "없는 카테고리입니다.";
	echo json_encode($arr);exit;
}

$sql = "select * from rb_cate2 as c2 where c1_idx = '$c1_idx' order by c2.c2_sort asc";
$data = sql_list($sql);

$arr = array();
$arr[result] = "success";
$arr[datas] = $data;
$arr[datas_cnt] = count($data);
echo json_encode($arr);exit;
?>