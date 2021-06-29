<?php
$menu_code = "100400";
$menu_mode = "w";
$crontab = 1;
$limit_access = "";
$limit_access_level = "worker";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

//p_arr($_POST);exit;
$query = $_POST['query'];


$search_sql = "";
$search_sql .= ($target_type != "0" && $target_type != "6" && $target_type != "3") ? " and os_type = '$target_type' " : "";
$search_sql .= ($tag2 != "") ? " and tag2 = '$tag2' " : "";
$search_sql .= ($tag3 != "") ? " and tag3 = '$tag3' " : "";

$mb_id = trim($mb_id);
if($mb_id != ""){
	$id_arr = explode(",", $mb_id);

	if(count($id_arr) > 0){
		$cnt = 0;
		$id_str_arr = array();
		foreach($id_arr as $k1 => $v1){
			$mb_id2 = trim($v1);
			$chk = get_member($mb_id2);
			if($chk['mb_id'] && $chk['mb_push'] == 1){
				$id_str_arr[] = $mb_id2;
				$cnt++;
			}
		}

		if(count($id_str_arr) > 0){
			$search_sql .= " and tag in ('".implode(',' , $id_str_arr)."')";
		}
	}
}

$rslt = pushcat_get_registred_cnt($sv_code, $search_sql);

$p_cnt = $rslt['p_cnt'];

if($p_cnt == 0){
	alert("푸쉬를 보낼수 있는 사용자가 없습니다.");
}


$push_contents = array();
$push_contents['msg'] = $_POST['pu_content'];
$push_contents['mode'] = "goto";
$push_contents['url'] = $_POST['pu_url'];

if($_POST['reserve'] == 1){
	pushcat_reserve_push($target_type, $mb_id, $tag2, $tag3, $push_contents, '', date("Y-m-d H:i:s", strtotime($_POST['rdate']." ".$_POST['rhour'].":".$_POST['rmin'].":00")));
	$msg = "푸쉬를 발송이 예약되었습니다.";
}else{
	pushcat_sendpush($target_type, $mb_id, $tag2, $tag3, $push_contents);
	$msg = "푸쉬를 발송하였습니다.";
}

alert($msg, "./member_push_insert.php");
?>