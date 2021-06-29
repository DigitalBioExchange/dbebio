<?
include "../_inc/_common.php";
header("Content-Type: application/json;charset=utf-8");

if($mb_email){
	if (!preg_match("/([0-9a-zA-Z_-]+)@([0-9a-zA-Z_-]+)\.([0-9a-zA-Z_-]+)/", $mb_email)) {
		$arr = array();
		$arr[result] = "error";
		$arr[msg] = ($user_agent == "web") ? '이메일 형식이 올바르지 않습니다.' : '이메일 형식이 올바르지 않습니다.';
		echo json_encode($arr);exit;
	}else{

		if($mb_id != ""){
			$tmp_sql = " and mb_id != '$mb_id' ";
		}
		$sql = "select count(*) from rb_member where mb_email = '".$mb_email."' and mb_status > 0 $tmp_sql";
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
			$arr[msg] = ($user_agent == "web") ? '중복된 이메일입니다.' : '중복된 이메일입니다.';
			echo json_encode($arr);exit;
		}
	}
}else{
	$arr = array();
	$arr[result] = "error";
	$arr[msg] = ($user_agent == "web") ? '이메일을 입력하세요.' : '이메일을 입력하세요.';
	echo json_encode($arr);exit;
}