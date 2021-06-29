<?
include "../_inc/_common.php";
header("Content-Type: application/json;charset=utf-8");

$bd_idx = $_GET[bd_idx];
$bd_pass = $_GET[bd_pass];

$chk = sql_fetch("select * from rb_board where bd_idx = '{$bd_idx}'");
if(!$chk[bd_idx]){
	$arr = array();
	$arr[result] = "error";
	$arr[msg] = "없는 글입니다.";
	echo json_encode($arr);exit;
}

if($bd_pass !== $chk[bd_pass] && !$is_admin){
	$arr = array();
	$arr[result] = "error";
	$arr[msg] = "비밀번호가 정확하지 않습니다.";
	echo json_encode($arr);exit;
}

$_SESSION['board_view'] = $chk[bd_idx];
$arr = array();
$arr[result] = "success";
$arr[msg] = "";
echo json_encode($arr);exit;
?>