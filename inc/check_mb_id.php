<?
include "../_inc/_common.php";
header("Content-Type: application/json;charset=utf-8");


if($mb_id){
	if (!preg_match("/(^[a-z0-9]+$)/", $mb_id) || strlen($mb_id) < 3 || strlen($mb_id) > 15) {
		$arr = array();
		$arr[result] = "error";
		$arr[msg] = ($user_agent == "web") ? '아이디는 영문소문자,숫자만 가능합니다.(3~15자)' : '아이디는 영문소문자,숫자만 가능합니다.(3~15자)';
		echo json_encode($arr);exit;
	}else{

		$sql = "select count(*) from rb_member where mb_id = '".$mb_id."'";
		$result= sql_query($sql);
		$data = mysql_fetch_array($result);
		if($data[0] == 0){
			$arr = array();
			$arr[result] = "success";
			$arr[msg] = "";
			echo json_encode($arr);exit;
		}else{
			$arr = array();
			$arr[result] = "error";
			$arr[msg] = ($user_agent == "web") ? '중복된 아이디입니다.' : '중복된 아이디입니다.';
			echo json_encode($arr);exit;
		}
	}
}else{
	$arr = array();
	$arr[result] = "error";
	$arr[msg] = ($user_agent == "web") ? '아이디를 입력해주세요.(영문소문자,숫자, 3~15자)' : '아이디를 입력해주세요.(영문소문자,숫자, 3~15자)';
	echo json_encode($arr);exit;
}