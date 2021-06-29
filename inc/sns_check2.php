<?
include "../_inc/_common.php";
header("Content-Type: application/json;charset=utf-8");

//p_arr($_POST);exit;
$ss_from = $_POST[ss_from];
$now_mode = $_POST[now_mode];

if(!$is_member){
	$arr = array();
	$arr[result] = "error";
	$arr[msg] = "로그인 후 이용하세요";
	echo json_encode($arr);exit;
}

$chk = sql_fetch("select * from rb_sns where ss_from = '$ss_from' and mb_id = '$member[mb_id]' ");

if($now_mode == 1){
	if(!$chk[ss_idx]){
		$arr = array();
		$arr[result] = "success";
		$arr[msg] = "";
		$arr[url] = "";
		print json_encode($arr);exit;
	}else{
		sql_query("update rb_sns set mb_id = '' where ss_idx = '$chk[ss_idx]'");
		$arr = array();
		$arr[result] = "success";
		$arr[msg] = "연결이 해제되었습니다.";
		$arr[url] = "";
		print json_encode($arr);exit;
	}
}


$arr = array();
$arr[result] = "success";
$arr[msg] = "";
$arr[url] = $url;
print json_encode($arr);exit;
?>