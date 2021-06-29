<?
include "../_inc/_common.php";
header("Content-Type: application/json;charset=utf-8");

$_is_ajax = false;
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
	&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ){
		$_is_ajax = true;
}

if ($_is_ajax != true) {
	$arr = array();
	$arr['result'] = "error";
	$arr['msg'] = "The wrong approach.";
	echo json_encode($arr);exit;
}

if(!$is_member){
	$arr = array();
	$arr['result'] = "error";
	$arr['msg'] = "Available after logging in.";
	echo json_encode($arr);exit;
}

if ($mode == 'set_data') {

	$sql = "update rb_member set mb_coin_address = '".$_POST['addr']."', mb_wallet_hash = '".$_POST['hash']."' where mb_idx = '".$member['mb_idx']."' ";
	sql_query($sql);

	$arr = array();
	$arr['result'] = "success";
	$arr['data'] = $_POST['hash'];
	$arr['msg'] = "The login account and meta mask account have been set up.";
	echo json_encode($arr);exit;

} else if ($mode == "check_data"){

	$sql = "select * from rb_member where mb_idx = '".$member['mb_idx']."' and mb_wallet_hash = '".$_POST['hash']."' and mb_coin_address = '".$_POST['addr']."' ";
	$data = sql_fetch($sql);

	if ($data['mb_idx']) {
		$arr = array();
		$arr['result'] = "success";
		$arr['data_hash'] = $_POST['hash'];
		$arr['data_addr'] = $_POST['addr'];
		$arr['msg'] = "";
		echo json_encode($arr);exit;

	} else {
		$arr = array();
		$arr['result'] = "error";
		$arr['msg'] = "The accounts metamask information does not match.";
		echo json_encode($arr);exit;

	}

}

$arr = array();
$arr['result'] = "error";
$arr['msg'] = 'The wrong approach.';
echo json_encode($arr);exit;
