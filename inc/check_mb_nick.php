<?
include "../_inc/_common.php";
header("Content-Type: application/json;charset=utf-8");

if($mb_nick){
	if (preg_match("/([^가-힣\x20^a-z^A-Z^0-9])/", $mb_nick)) {
		$arr = array();
		$arr[result] = "error";
		$arr[msg] = ($user_agent == "web") ? '한글, 영문, 숫자만 가능합니다.' : '한글, 영문, 숫자만 가능합니다.';
		echo json_encode($arr);exit;
	}else{
		if($mb_id != ""){
			$tmp_sql = " and mb_id != '$mb_id' ";
		}
		$sql = "select count(*) from rb_member where mb_nick = '".$mb_nick."' and mb_status > 0 $tmp_sql";
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
			$arr[msg] = ($user_agent == "web") ? '중복된 닉네임입니다.' : '중복된 닉네임입니다.';
			echo json_encode($arr);exit;
		}
	}
}else{
	$arr = array();
	$arr[result] = "error";
	$arr[msg] = ($user_agent == "web") ? '닉네임을 입력하세요.' : '닉네임을 입력하세요.';
	echo json_encode($arr);exit;
}
?>