<?
include "../_inc/_common.php";
header("Content-Type: application/json;charset=utf-8");

$bd_idx = $_GET[bd_idx];

if(!$is_member){
	$arr = array();
	$arr[result] = "error";
	$arr[msg] = "로그인 후 이용하세요";
	echo json_encode($arr);exit;
}

$chk = sql_fetch("select * from rb_board where bd_idx = '{$bd_idx}'");
if(!$chk[bd_idx]){
	$arr = array();
	$arr[result] = "error";
	$arr[msg] = "없는 글입니다.";
	echo json_encode($arr);exit;
}


$chk_scrap = sql_fetch("select * from rb_board_scrap where bd_idx = '$bd_idx' and mb_id = '$member[mb_id]'");
if($chk_scrap[sc_idx]){

	$arr = array();
	$arr[result] = "error";
	$arr[msg] = "이미 스크랩한 글입니다.";
	echo json_encode($arr);exit;
}else{
	sql_query("insert into rb_board_scrap set bd_idx = '$bd_idx', mb_id = '$member[mb_id]', sc_regdate = now()");
	$scrap_cnt = sql_total("select * from rb_board_scrap where bd_idx = '$bd_idx'");
	sql_query("update rb_board set bd_scrap_cnt = $scrap_cnt where bd_idx = '{$bd_idx}'");
	$arr = array();
	$arr[result] = "success";
	$arr[scrap_result] = "1";
	$arr[scrap_cnt] = $scrap_cnt;
	$arr[msg] = "스크랩되었습니다.";
	echo json_encode($arr);exit;
}
?>