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

if ($mb_type == 'find_pw') {

	$_GET['mb_hp'] = split_tel_number($_GET['mb_hp']);
	$sql_chk = "select * from rb_member where mb_hp = '".$_GET['mb_hp']."' and mb_n_telnum = '".$_GET['mb_n_telnum']."' and mb_status = 1 ";
	$data_chk = sql_fetch($sql_chk);
	if (!$data_chk['mb_idx']) {
		$arr = array();
		$arr['result'] = "error";
		$arr['msg'] = $_lang['inc']['text_0746'];
		echo json_encode($arr);exit;
	}

}

if (!$_GET['mb_hp'] || !$_GET['mb_n_telnum']) {
	$arr = array();
	$arr['result'] = "error";
	$arr['msg'] = $_lang['tpl_member']['text_0333'];
	echo json_encode($arr);exit;
}

if ($mb_type == 'insert') {

	$_GET['mb_hp'] = split_tel_number($_GET['mb_hp']);
	$sql_chk = "select * from rb_member where mb_hp = '".$_GET['mb_hp']."' and mb_n_telnum = '".$_GET['mb_n_telnum']."' and mb_status = 1 ";
	$data_chk = sql_fetch($sql_chk);
	if ($data_chk['mb_idx']) {
		$arr = array();
		$arr['result'] = "error";
		$arr['msg'] = $_lang['member']['text_0788'];
		echo json_encode($arr);exit;
	}

}

$phone = str_replace("-", "", trim($mb_hp));

$mb_hp = str_replace("-", "", trim($mb_hp));
$phone = (substr($mb_hp, 0, 1) == 0) ? substr($mb_hp, 1) : $mb_hp;

$ce_num = sprintf('%04d', rand(0, 9999));
$session_id = session_id();

//3분후에 다시 발송가능
$sql_chk = "select * from rb_certi where ce_session = '$session_id' and ce_end > '".date("Y-m-d H:i:s", strtotime("-3 minutes"))."' ";
$data_chk = sql_fetch($sql_chk);
if ($data_chk) {
	$arr = array();
	$arr['result'] = "success";
	$arr['msg'] = $_lang['inc']['text_0747'];
	echo json_encode($arr);exit;
}

$chk = sql_fetch("select * from rb_certi where ce_session = '$session_id'");
if($chk['ce_idx']){
	sql_query("update rb_certi set ce_num = '$ce_num', ce_end = now() where ce_session = '$session_id'");
}else{
	sql_query("insert into rb_certi set ce_session = '$session_id', ce_num = '$ce_num', ce_end = now()");
}

if($mb_n_telnum == "" || $phone == ""){
	alert("".$_lang['inc']['text_0735']."");
}

$to_num = $mb_n_telnum.$phone;
if ($lang_code == 'ko') {
	$msg = "[DBE] 인증번호는 [".$ce_num."] 입니다.";
} else {
	$msg = "[DBE] The authentication number is [".$ce_num."]";
}

$rslt = infobip_sms($to_num, $msg);

$arr = array();
$arr['result'] = "success";
$arr['msg'] = "".$_lang['inc']['text_0737']."";
$arr['rslt'] = $rslt;
echo json_encode($arr);exit;
?>