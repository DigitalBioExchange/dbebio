<?
include "../_inc/_common.php";
header("Content-Type: application/json;charset=utf-8");

// echo json_encode($_POST);exit;
$_is_ajax = false;
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
	&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ){
		$_is_ajax = true;
}

if ($_is_ajax != true) {
	$arr = array();
	$arr['result'] = "error";
	$arr['msg'] = $_lang['inc']['text_0696'];
	echo json_encode($arr);exit;
}

if(!$is_member){
	$arr = array();
	$arr['result'] = "error";
	$arr['msg'] = $_lang['inc']['text_0697'];
	echo json_encode($arr);exit;
}

if ($mode == 'search') {

	if ($n_tel != '') {
		$sql = "select * from rb_country_tel_light where ct_tel_val = '".$n_tel."' ";
		$data = sql_fetch($sql);
		if ($data['ct_delivery_price']) {
			$arr = array();
			$arr['result'] = "success";
			$arr['ct_delivery_price'] = $data['ct_delivery_price'];
			$arr['msg'] = "";
			echo json_encode($arr);exit;

		} else {
			$arr = array();
			$arr['result'] = "error";
			$arr['msg'] = $_lang['inc']['text_0712'];
			echo json_encode($arr);exit;

		}


	} else {
		$arr = array();
		$arr['result'] = "error";
		$arr['msg'] = $_lang['inc']['text_0712'];
		echo json_encode($arr);exit;

	}

} 

echo json_encode(array('code' => "error", 'msg' => $_lang['inc']['text_0696']));
exit;