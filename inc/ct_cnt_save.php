<?
include "../_inc/_common.php";
header("Content-Type: application/json;charset=utf-8");

$ct_idx = $_POST['ct_idx'];
$ct_cnt = $ct_cnt;

$where_query = ($is_member) ? "and mb_id = '".$member['mb_id']."'" : "and mb_session = '".session_id()."'";
sql_query("update rb_cart set ct_cnt = '$ct_cnt' where ct_idx = '$ct_idx' $where_query");

$arr = array();
$arr['result'] = "success";
//$arr[msg] = "변경되었습니다.";
echo json_encode($arr);exit;
?>