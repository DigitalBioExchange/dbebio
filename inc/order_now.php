<?
include "../_inc/_common.php";
header("Content-Type: application/json;charset=utf-8");

$pd_idx = $_POST['pd_idx'];
$product_option_val = $_POST['product_option_val'];

$chk = sql_fetch("select * from rb_product where pd_idx = '{$pd_idx}'");
if(!$chk['pd_idx']){
	$arr = array();
	$arr['result'] = "error";
	$arr['msg'] = $_lang['shop']['text_0694'];
	echo json_encode($arr);exit;
}

if($product_option_val == ""){
	$arr = array();
	$arr['result'] = "error";
	$arr['msg'] = $_lang['shop']['text_0695'];
	echo json_encode($arr);exit;
}

$product_option_val_arr = explode("|||", $product_option_val);

$get_ct_num = sql_fetch("select max(ct_num) as mx from rb_cart_temp where 1");
$ct_num = $get_ct_num['mx'] + 1;

sql_query("delete from rb_cart_temp where ct_regdate < DATE_ADD(now(), interval -2 day)");

foreach($product_option_val_arr as $k1 => $v1){
	$item_data_arr = explode("|;|", $v1);
	$ct_cnt = (int)$item_data_arr[1];

	$i_data_arr = explode("|:|", $item_data_arr[0]);

	$ct_option = "";
	$idt_arr = explode(":", $i_data_arr[0]);
	$ct_option = $idt_arr[0];

	if($i_data_arr[1]){
		$idt_arr = explode(":", $i_data_arr[1]);
		$ct_option .= ":".$idt_arr[0];
	}

	$where_query = ($is_member) ? "and mb_id = '".$member['mb_id']."'" : "and mb_session = '".session_id()."'";

	sql_query("insert into rb_cart_temp set ct_num = '".$ct_num."', pd_idx = '".$pd_idx."', mb_session = '".session_id()."', mb_id = '".$member['mb_id']."', ct_cnt = $ct_cnt, ct_option = '$ct_option', ct_regdate = now()");
}

$ct_num_len = strlen($ct_num);
$rslt = $ct_num_len.make_random_numstring(10 - $ct_num_len).$ct_num;

$arr = array();
$arr['result'] = "success";
$arr['ct_num'] = $rslt;
echo json_encode($arr);exit;
?>