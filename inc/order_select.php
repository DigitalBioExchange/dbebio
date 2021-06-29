<?
include "../_inc/_common.php";
header("Content-Type: application/json;charset=utf-8");

$ct_idx_str = $_POST['ct_idx_str'];


if($ct_idx_str == ""){
	$arr = array();
	$arr['result'] = "error";
	$arr['msg'] = $_lang['tpl_shop']['text_0361'];
	echo json_encode($arr);exit;
}

sql_query("delete from rb_cart_temp where ct_regdate < DATE_ADD(now(), interval -2 day)");

$get_ct_num = sql_fetch("select max(ct_num) as mx from rb_cart_temp where 1");
$ct_num = $get_ct_num['mx'] + 1;

$where_query = ($is_member) ? "and mb_id = '".$member['mb_id']."'" : "and mb_session = '".session_id()."'";
$sql = "
INSERT INTO `rb_cart_temp` 
(select '', '$ct_num', `ct_idx`, `mb_id`, `mb_session`, `pd_idx`, `ct_cnt`, `ct_option`, `cr_idx`, now() from rb_cart where ct_idx in ($ct_idx_str) $where_query order by pd_idx desc)
";
sql_query($sql);

$ct_num_len = strlen($ct_num);
$rslt = $ct_num_len.make_random_numstring(10 - $ct_num_len).$ct_num;

$arr = array();
$arr['result'] = "success";
$arr['ct_num'] = $rslt;
echo json_encode($arr);exit;
?>