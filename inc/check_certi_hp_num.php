<?
include "../_inc/_common.php";
header("Content-Type: application/json;charset=utf-8");

// echo json_encode($_GET);exit;

$_is_ajax = false;
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
	&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ){
		$_is_ajax = true;
}

if ($_is_ajax != true) {
	$arr = array();
	$arr['result'] = "error";
	$arr['msg'] = "".$_lang['inc']['text_0696']."";
	echo json_encode($arr);exit;
}


$session_id = session_id();

$chk = sql_fetch("select * from rb_certi where ce_session = '$session_id' and ce_num = '$ce_num'");
if($chk['ce_idx']){
	$arr = array();
	$arr['result'] = "success";
	$arr['msg'] = $_lang['inc']['text_0710'];
	$arr['rslt'] = $rslt;
	echo json_encode($arr);exit;
}else{
	$arr = array();
	$arr['result'] = "error";
	$arr['msg'] = $_lang['inc']['text_0711'];
	echo json_encode($arr);exit;
}
?>