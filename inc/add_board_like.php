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


$chk_like = sql_fetch("select * from rb_board_like where bd_idx = '$bd_idx' and mb_id = '$member[mb_id]'");
if($chk_like[li_idx]){
	sql_query("delete from rb_board_like where li_idx = '$chk_like[li_idx]'");
	$like_cnt = sql_total("select * from rb_board_like where bd_idx = '$bd_idx'");
	sql_query("update rb_board set bd_like_cnt = $like_cnt where bd_idx = '{$bd_idx}'");
	$arr = array();
	$arr[result] = "success";
	$arr[like_result] = "0";
	$arr[like_cnt] = $like_cnt;
	$arr[msg] = "좋아요가 취소되었습니다.";
	echo json_encode($arr);exit;
}else{
	sql_query("insert into rb_board_like set bd_idx = '$bd_idx', mb_id = '$member[mb_id]', li_regdate = now()");
	$like_cnt = sql_total("select * from rb_board_like where bd_idx = '$bd_idx'");
	sql_query("update rb_board set bd_like_cnt = $like_cnt where bd_idx = '{$bd_idx}'");
	$arr = array();
	$arr[result] = "success";
	$arr[like_result] = "1";
	$arr[like_cnt] = $like_cnt;
	$arr[msg] = "좋아요 되었습니다.";
	echo json_encode($arr);exit;
}
?>