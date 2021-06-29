<?
include "../_inc/_common.php";
header("Content-Type: application/json;charset=utf-8");


if($mb_id){
	$sql = "select * from rb_member where mb_id = '".$mb_id."'";
	$data = sql_fetch($sql);
	if($data[mb_id]){
		if($data[mb_status] == 1){
			$arr = array();
			$arr[result] = "success";
			$arr[msg] = $data[mb_name]."님은 발급이 가능한 회원입니다.";
			echo json_encode($arr);exit;
		}else{
			$arr = array();
			$arr[result] = "error";
			$arr[msg] = get_txt_from_data($_cfg['member']['mb_status'], $data[mb_status])."입니다.";
			echo json_encode($arr);exit;
		}
	}else{
		$arr = array();
		$arr[result] = "error";
		$arr[msg] = "존재하지 않는 회원입니다.";
		echo json_encode($arr);exit;
	}
}