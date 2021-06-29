<?
include "../_inc/_common.php";
header("Content-Type: application/json;charset=utf-8");

//p_arr($_POST);exit;
$ss_from = $_POST[ss_from];
$action = $_POST[action];
$access_token = $_POST[access_token];
$ss_id = $_POST[ss_id];
$ss_email = $_POST[ss_email];
$ss_photo = $_POST[ss_photo];

$chk = sql_fetch("select * from rb_sns where ss_from = '$ss_from' and ss_id = '$ss_id' ");

if($action == "login"){
	if($ss_from == "facebook" || $ss_from == "kakao" || $ss_from == "naver"){
		if($chk[ss_idx]){
			sql_query("
			update rb_sns set
				ss_access_token = '$access_token',
				ss_email = '$ss_email',
				ss_photo = '$ss_photo'
				where ss_idx = '$chk[ss_idx]'
			");

			if($chk[mb_id]){
				$mb = get_member($chk[mb_id]);

				if($mb[mb_certified] != 1 && $mb[mb_level] < $_cfg[subadmin_level]){
					$arr = array();
					$arr[result] = "error";
					$arr[msg] = "이메일 인증후에 이용이 가능합니다.";
					print json_encode($arr);exit;
				}
				if($mb[mb_status] == 2){
					$arr = array();
					$arr[result] = "error";
					$arr[msg] = "차단된상태입니다. 관리자에게 문의바랍니다.";
					print json_encode($arr);exit;
				}

				if($mb[mb_status] == 3){

					sql_query("
					update rb_sns set
						mb_id = '',
						where ss_idx = '$chk[ss_idx]'
					");

					$_SESSION['ss_idx'] = $chk[ss_idx];
					$url = "/member/join.php";

					$arr = array();
					$arr[result] = "success";
					$arr[msg] = "";
					$arr[url] = $url;
					print json_encode($arr);exit;
				}

				$_SESSION['ss_mb_idx'] = $mb[mb_idx];
				$_SESSION['ss_mb_id'] = $mb[mb_id];
				$_SESSION['ss_mb_nick'] = $mb[mb_nick];
				$_SESSION['ss_mb_level'] = $mb[mb_level];

				sql_query("update rb_member set mb_lastlogin = mb_nowlogin where mb_id = '$mb[mb_id]'");
				sql_query("update rb_member set mb_nowlogin = now() where mb_id = '$mb[mb_id]'");

				sql_query("insert into rb_login_history set mb_id = '$mb[mb_id]', lh_regdate = now(), lh_year = '".date("Y")."', lh_month = '".date("n")."', lh_day = '".date("j")."'");

				$url = "/index.php";

			}else{
				$_SESSION['ss_idx'] = $chk[ss_idx];
				$url = "/member/join.php";
			}
		}else{
			sql_query("
			insert into rb_sns set
				ss_from = '$ss_from',
				ss_access_token = '$access_token',
				ss_id = '$ss_id',
				ss_email = '$ss_email',
				ss_photo = '$ss_photo'
			");
			$ss_idx = mysql_insert_id();

			$_SESSION['ss_idx'] = $ss_idx;

			$url = "/member/join.php";
		}

		$arr = array();
		$arr[result] = "success";
		$arr[msg] = "";
		$arr[url] = $url;
		print json_encode($arr);exit;

	}

}else if($action == "link"){

	if(!$is_member){
		$arr = array();
		$arr[result] = "error";
		$arr[msg] = "로그인 후 이용하세요";
		echo json_encode($arr);exit;
	}

	if($ss_from == "facebook" || $ss_from == "kakao" || $ss_from == "naver"){
		if($chk[ss_idx]){
			sql_query("
			update rb_sns set
				ss_access_token = '$access_token',
				ss_email = '$ss_email',
				ss_photo = '$ss_photo'
				where ss_idx = '$chk[ss_idx]'
			");

			sql_query("update rb_sns set mb_id = '".$member[mb_id]."' where ss_idx = '$chk[ss_idx]'");
		}else{
			sql_query("
			insert into rb_sns set
				ss_from = '$ss_from',
				ss_access_token = '$access_token',
				ss_id = '$ss_id',
				ss_email = '$ss_email',
				ss_photo = '$ss_photo'
			");
			$ss_idx = mysql_insert_id();

			sql_query("update rb_sns set mb_id = '".$member[mb_id]."' where ss_idx = '$ss_idx'");
		}
		$arr = array();
		$arr[result] = "success";
		$arr[msg] = "연결되었습니다.";
		$arr[url] = "";
		print json_encode($arr);exit;
	}
}

$arr = array();
$arr[result] = "error";
$arr[msg] = "";
print json_encode($arr);exit;
?>