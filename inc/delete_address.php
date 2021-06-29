<?
include "../_inc/_common.php";
header("Content-Type: application/json;charset=utf-8");

$ad_idx = $_GET[adress_idx];

if(!$is_member){
	$arr = array();
	$arr[result] = "error";
	$arr[msg] = "로그인 후 이용하세요";
	echo json_encode($arr);exit;
}

sql_query("delete from rb_address where ad_idx = '$ad_idx'");


$address = sql_list("select * from rb_address where mb_id = '".$member[mb_id]."' order by ad_idx desc");


for($i=0;$i<count($address);$i++){
	$address[$i][ad_name_v] = get_text($address[$i][ad_name]);
	$address[$i][ad_tel_v] = get_text($address[$i][ad_tel]);
	$address[$i][ad_addr1_v] = get_text($address[$i][ad_addr1]);
	$address[$i][ad_addr2_v] = get_text($address[$i][ad_addr2]);
}


$arr = array();
$arr[result] = "success";
$arr[datas] = $address;
$arr[data_cnt] = count($address);
echo json_encode($arr);exit;
?>