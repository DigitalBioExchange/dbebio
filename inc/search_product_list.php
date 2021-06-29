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

$search_query = "";
if($_GET[c1_idx] != ""){
	$search_query .= " and p.c1_idx = '$_GET[c1_idx]' ";
}

if($_GET[c2_idx] != ""){
	$search_query .= " and p.c2_idx = '$_GET[c2_idx]' ";
}

if($_GET[c3_idx] != ""){
	$search_query .= " and p.c3_idx = '$_GET[c3_idx]' ";
}

if($_GET[stx]){
	$search_query .= " and p.pd_name like '%$_GET[stx]%' ";
}


$sql = "select * from rb_product as p left join rb_cate1 as c1 on c1.c1_idx = p.c1_idx left join rb_cate2 as c2 on c2.c2_idx = p.c2_idx left join rb_cate3 as c3 on c3.c3_idx = p.c3_idx where pd_idx != '{$pd_idx}' $search_query order by c1_sort asc, c2_sort asc, c3_sort asc";
$data = sql_list($sql);

$arr = array();
$arr[result] = "success";
$arr[datas] = $data;
$arr[datas_cnt] = count($data);
echo json_encode($arr);exit;
?>