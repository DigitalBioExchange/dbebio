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
	$arr[result] = "error";
	$arr[msg] = "".$_lang['inc']['text_0696']."";
	echo json_encode($arr);exit;
}


$mtype = "sms";
$name = iconv("UTF-8", "EUC-KR", '');
if (!preg_match("/([0-9a-zA-Z_-]+)@([0-9a-zA-Z_-]+)\.([0-9a-zA-Z_-]+)/", $mb_id)) {
	$arr = array();
	$arr[result] = "error";
	$arr[msg] = "".$_lang['inc']['text_0738']."";
	echo json_encode($arr);exit;
}


$ce_num = sprintf('%04d', rand(0, 9999));
$session_id = session_id();

$chk = sql_fetch("select * from rb_certi_email where ce_session = '$session_id'");
if($chk[ce_idx]){
	sql_query("update rb_certi_email set ce_num = '$ce_num', ce_end = now() where ce_session = '$session_id'");
}else{
	sql_query("insert into rb_certi_email set ce_session = '$session_id', ce_num = '$ce_num', ce_end = now()");
}

ob_start();
include_once ("./email_certi.html");
$content = ob_get_contents();
ob_end_clean();
$content = str_replace("[certi_num]", $ce_num, $content);
$content = str_replace("[URL]", $_cfg['url'], $content);

$rslt = sendMail($mb_id, $_cfg['site_email'], $_cfg['site_name'], $_cfg['site_name'].' '.$_lang['inc']['text_0739'].'', $content);

$arr = array();
$arr[result] = "success";
$arr[msg] = "".$_lang['inc']['text_0740']."";
$arr[rslt] = $rslt;
echo json_encode($arr);exit;
?>