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
	$arr['msg'] = "".$_lang['inc']['text_0696']."";
	echo json_encode($arr);exit;
}

if ($body_fat_rate && $BMR && $metabolic_age) {

	$sql_inc = "insert into rb_body_fat set 
							mb_idx = '".$member['mb_idx']."',
							mb_id = '".$member['mb_id']."',
							weight = '".$weight."', 
							BMI = '".$BMI."', 
							body_fat_rate = '".$body_fat_rate."', 
							subcutaneous_fat = '".$subcutaneous_fat."', 
							visceral_fat = '".$visceral_fat."', 
							body_water_rate = '".$body_water_rate."', 
							muscle_rate = '".$muscle_rate."', 
							bone_mass = '".$bone_mass."', 
							BMR = '".$BMR."', 
							body_type = '".$body_type."', 
							protein = '".$protein."',
							muscle_mass = '".$muscle_mass."',
							metabolic_age = '".$metabolic_age."',
							heart_rate = '".$heart_rate."',
							bf_regdate = now()
						";
	$sql_q = sql_query($sql_inc);
	$bf_idx = sql_insert_id();

	//1일 1회 디바이스 측정 보상
	$sql = "select * from rb_point_setting where ps_idx = 5";
	$data_chk = sql_fetch($sql);
	if ($data_chk['ps_status'] == 1) {
		$sql_ph = "select * from rb_point_history where mb_id = '".$member['mb_id']."' and ph_type = '".$data_chk['ps_idx']."' and date_format(ph_regdate, '%Y-%m-%d') = '".date('Y-m-d')."' ";
		$data_ph = sql_fetch($sql_ph);
		if (!$data_ph['ph_idx']) {
			$msg_ko = "1일 1회 디바이스 측정 보상 지급 - ".$macData;
			$msg_en = "Device measurement compensation paid once a day - ".$macData;
			write_member_point($member['mb_id'], $data_chk['ps_point'], $data_chk['ps_idx'], $msg_ko, $msg_en);
		}
		
	}

	//월 4회 디바이스 측정보상
	$sql = "select * from rb_point_setting where ps_idx = 6";
	$data_chk = sql_fetch($sql);
	if ($data_chk['ps_status'] == 1) {
		$sql_ph = "select * from rb_point_history where mb_id = '".$member['mb_id']."' and ph_type = '".$data_chk['ps_idx']."' and date_format(ph_regdate, '%Y-%m') = '".date('Y-m')."' ";
		$data_ph = sql_fetch($sql_ph);
		if (!$data_ph['ph_idx']) {

			//월 4회 측정확인
			$sql_ph2 = "select * from rb_point_history where mb_id = '".$member['mb_id']."' and ph_type = '5' and date_format(ph_regdate, '%Y-%m') = '".date('Y-m')."' ";
			$data_ph2 = sql_list($sql_ph2);
			if (count($data_ph2) > 3) {
				$msg_ko = "월 4회 디바이스 측정보상 지급 - ".$macData;
				$msg_en = "Device measurement compensation 4 times a month - ".$macData;
				write_member_point($member['mb_id'], $data_chk['ps_point'], $data_chk['ps_idx'], $msg_ko, $msg_en);

			}
			
		}
		
	}


	$arr = array();
	$arr['result'] = "success";
	$arr['idx'] = $bf_idx;
	$arr['datas_cnt'] = count($_POST);
	echo json_encode($arr);exit;

} else if ($mode == "point_device") {

	$popup = 'off';

	//최초 디바이스 연동보상
	$sql = "select * from rb_point_setting where ps_idx = 4";
	$data_chk = sql_fetch($sql);
	if ($data_chk['ps_status'] == 1) {
		$sql_ph = "select * from rb_point_history where mb_id = '".$member['mb_id']."' and ph_type = '".$data_chk['ps_idx']."' ";
		$data_ph = sql_fetch($sql_ph);
		if (!$data_ph['ph_idx']) {
			$msg_ko = "최초 디바이스 연동보상 지급 - ".$mac;
			$msg_en = "First device linkage compensation - ".$mac;
			write_member_point($member['mb_id'], $data_chk['ps_point'], $data_chk['ps_idx'], $msg_ko, $msg_en);
			$popup = 'on';

		}
		
	}

	//회원테이블에 mac데이터 넣기
	// $sql_upd = "update rb_member set mb_jongmok = '".$mac."' where mb_idx = '".$member['mb_idx']."' ";
	// sql_query($sql_upd);

	$arr = array();
	$arr['result'] = "success";
	$arr['popup'] = $popup;
	echo json_encode($arr);exit;


} else{
	$arr = array();
	$arr['result'] = "error";
	$arr['idx'] = $bf_idx;
	$arr['datas_cnt'] = count($_POST);
	$arr['msg'] = $_lang['measuring']['text_0799'];
	echo json_encode($arr);exit;

}

// $chk = sql_fetch("select * from rb_cate1 as c1 where c1.c1_idx = '$c1_idx' ");
// if(!$chk['c1_idx']){
// 	$arr = array();
// 	$arr['result'] = "error";
// 	$arr['msg'] = "없는 카테고리입니다.";
// 	echo json_encode($arr);exit;
// }

// $sql = "select * from rb_cate2 as c2 where c1_idx = '$c1_idx' order by c2.c2_sort asc";
// $data = sql_list($sql);
?>