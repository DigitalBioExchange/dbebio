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
	$arr['msg'] = "The wrong approach.";
	echo json_encode($arr);exit;
}

if(!$is_member){
	$arr = array();
	$arr['result'] = "error";
	$arr['msg'] = "Available after logging in.";
	echo json_encode($arr);exit;
}

if ($mode == 'insert') {
	//log 등록
	coin_log('insert_before', $_POST, '', addslashes($_POST['hash']));

	//해쉬값이 DB에 존재하는지 확인
	$sql_hash = "select * from rb_order where od_tno = '".addslashes($_POST['hash'])."' ";
	$data_hash = sql_fetch($sql_hash);

	if ($data_hash['od_idx']) {

		//log 등록
		coin_log('insert_error_od_idx', $_POST, '', addslashes($_POST['hash']));

		$arr = array();
		$arr['result'] = "error";
		$arr['sql'] = $sql_hash;
		$arr['msg'] = "This is a transaction value that already exists.";
		echo json_encode($arr);exit;

	} else {

		$sql = "select * from rb_order where od_idx = '".addslashes($od_idx)."' ";
		$data = sql_fetch($sql);

		if ($data['od_idx']) {
			
			$sql_upd = "update rb_order set 
									od_tno = '".addslashes($_POST['hash'])."', 
									od_status = 1, 
									od_coin_status = 1
									where od_idx = '".addslashes($_POST['od_idx'])."'
							";
			sql_query($sql_upd);
			// $od_idx = sql_insert_id();

			$sql_upd = "update rb_order_cart set 
									ct_status = 1
									where od_idx = '".addslashes($_POST['od_idx'])."'
							";
			sql_query($sql_upd);

			// $al_contents = "A transaction for ".$data['pd_name']." products is pending.";

			// $sql_ins = "insert into rb_alarm_list set 
			// 						mb_idx = '".$member['mb_idx']."',
			// 						mb_id = '".$member['mb_id']."',
			// 						al_contents = '".addslashes($al_contents)."',
			// 						al_regdate = now()
			// 				";
			// sql_query($sql_ins);
			// $al_idx = sql_insert_id();

			//상품에 구매자 업데이트
			// $sql_upd = "update rb_product set 
			// 						pd_buy_idx = '".$member['mb_idx']."'
			// 						where pd_idx = '".$data['pd_idx']."'
			// 					";
			// sql_query($sql_upd);

			//log 등록
			coin_log('insert_after', $_POST, '', addslashes($_POST['hash']));

			$arr = array();
			$arr['result'] = "success";
			$arr['day'] = date('Y-m-d');
			$arr['msg'] = "";
			echo json_encode($arr);exit;

		} else {
			//log 등록
			coin_log('insert_error_od_idx', $_POST, '', addslashes($_POST['hash']));

			$arr = array();
			$arr['result'] = "error";
			$arr['msg'] = "Incorrect product information.";
			echo json_encode($arr);exit;
		}
	

	}


} else if ($mode == 'update') {
	//log 등록
	coin_log('update_before', $_POST, '', addslashes($_POST['hash']));

	$sql = "select * from rb_order where od_tno = '".addslashes($_POST['hash'])."' and od_coin_status = 1 ";
	$data = sql_fetch($sql);

	if ($data['od_idx']) {

		// 트랜잭션 해쉬로 거래내용 확인
		$_param = array(
			'module' => 'proxy',
			'action' => 'eth_getTransactionByHash',
			'txhash' => addslashes($_POST['hash']),
			'apikey' => 'DXQCBSEBE2GJKXSESV1FYGNDJCYUM78T2F',
		);

		// GET방식
		if ($user_agent == 'web') {
			$_url = $_cfg['eth']['url_web']."?".http_build_query($_param);
		} else {
			$_url = $_cfg['eth']['url']."?".http_build_query($_param);
		}

		$curlObj = curl_init();
		curl_setopt($curlObj, CURLOPT_URL, $_url);
		curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curlObj, CURLOPT_HEADER, 0);
		$response = curl_exec($curlObj);
		$_json = json_decode($response,true);
		curl_close($curlObj);

		$_t_hash = $_json['result']['hash'];
		$_to = $_json['result']['to'];

		//log 등록
		coin_log('update_curl', $_POST, $_json, addslashes($_POST['hash']));

		if ($user_agent == 'web') {
			$_sever_to = strtoupper($_cfg['coin']['contract_web']);
		} else {
			$_sever_to = strtoupper($_cfg['coin']['contract']);
		}
		
		//log등록
		$sql_log = "insert into _coin_log set 
							mb_idx = '".$member['mb_idx']."',
							col_position = 'update_verification',
							col_hash_ver = '".strtoupper($_t_hash)."',
							col_hash_server = '".strtoupper(addslashes($_POST['hash']))."',
							col_from_ver = '".strtoupper($_to)."',
							col_from_server = '".$_sever_to."',
							col_regdate = now()
						";
		sql_query($sql_log);

		if (strtoupper($_t_hash) == strtoupper(addslashes($_POST['hash'])) && strtoupper($_to) == $_sever_to) {


			//구매정보에 거래완료 업데이트
			$sql_upd = "update rb_order set 
									od_status = 2, 
									od_coin_status = 2, 
									od_paydate = now()
									where od_idx = '".$data['od_idx']."'
							";
			sql_query($sql_upd);

			$sql_upd = "update rb_order_cart set 
									ct_status = 2
									where od_idx = '".$data['od_idx']."'
							";
			sql_query($sql_upd);

			// $al_contents = "The transaction of ".$data['pd_name']." goods has been completed.";

			// //거래완료 알림 발송
			// $sql_ins = "insert into rb_alarm_list set 
			// 						mb_idx = '".$member['mb_idx']."',
			// 						mb_id = '".$member['mb_id']."',
			// 						al_contents = '".addslashes($al_contents)."',
			// 						al_regdate = now()
			// 				";
			// sql_query($sql_ins);
			// $al_idx = sql_insert_id();


			//구매테이블 작성
			// $sql_check = "select * from rb_product where pd_idx = '".$data['pd_idx']."' ";
			// $data_check = sql_fetch($sql_check);
			// $sql_ins = "insert into rb_product_buyer set 
			// 						pd_idx = '".$data_check['pd_idx']."', 
			// 						pd_img_url = '".$data_check['pd_img_url']."', 
			// 						od_idx = '".$data['od_idx']."', 
			// 						mb_idx = '".$member['mb_idx']."', 
			// 						mb_id = '".$member['mb_id']."', 
			// 						pb_price = '".$data_check['pd_price']."', 
			// 						pb_coin_hash = '".$data['od_tno']."', 
			// 						pb_regdate = now()
			// 					";
			// sql_query($sql_ins);

			//뷰 히스토리 작성
			// $sql_his = "insert into rb_product_view_history set 
			// 						ph_type = 2,
			// 						od_idx = '".$data['od_idx']."',
			// 						pd_idx = '".$data['pd_idx']."',
			// 						mb_idx = '".$member['mb_idx']."',
			// 						mb_id = '".$member['mb_id']."',
			// 						ph_regdate = now()
			// 					";
			// sql_query($sql_his);

			//log 등록
			coin_log('update_after', $_POST, $_json, addslashes($_POST['hash']));
		

			$arr = array();
			$arr['result'] = "success";
			$arr['day'] = date('Y-m-d');
			$arr['msg'] = "";
			echo json_encode($arr);exit;

		} else {
			//log 등록
			coin_log('update_error_curl_ver', $_POST, '', addslashes($_POST['hash']));

			$arr = array();
			$arr['result'] = "error";
			$arr['msg'] = "There is no transaction information.";
		}

	} else {
		//log 등록
		coin_log('update_error_od_idx', $_POST, $_json, addslashes($_POST['hash']));


		$arr = array();
		$arr['result'] = "error";
		$arr['msg'] = "The purchase information is incorrect.";
		echo json_encode($arr);exit;
	}
}

echo json_encode(array('code' => "error", 'msg' => "The wrong approach."));
exit;