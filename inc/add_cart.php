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
	$chk_cart = sql_fetch("select * from rb_cart where pd_idx = '$pd_idx' and ct_option = '$ct_option' $where_query");

	if($chk_cart['ct_idx']){
		sql_query("update rb_cart set ct_cnt = ct_cnt + $ct_cnt where ct_idx = '".$chk_cart['ct_idx']."'");
	}else{
		sql_query("insert into rb_cart set pd_idx = '".$pd_idx."', mb_session = '".session_id()."', mb_id = '".$member['mb_id']."', ct_cnt = $ct_cnt, ct_option = '$ct_option'");
	}
}

$arr = array();
$arr['result'] = "success";
echo json_encode($arr);exit;
?>