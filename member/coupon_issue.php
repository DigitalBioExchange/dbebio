<?php
include "../inc/_common.php";
include "../inc/_head.php";
goto_login();

	$query = $_POST['query'];
	$cr_code = str_replace("-", "", $_POST['cr_code']);
	$data = sql_fetch("select * from rb_coupon_record as cr left join rb_coupon as c on cr.cp_idx = c.cp_idx where cr_code = '$cr_code' and mb_id = '' ");

	if(!$data['cr_idx']) alert("없는 쿠폰입니다.");

	$chk_dup = sql_fetch("select * from rb_coupon_record where mb_id = '".$member['mb_id']."' and cp_idx = '".$data['cp_idx']."'");
	if($chk_dup['cr_idx']){
		alert("이미 발급받은 쿠폰입니다.");
	}

	if($data['cp_period_type'] == '1'){
		$cr_s_date = date("Y-m-d");
		$cr_e_date = date("Y-m-d", strtotime("+ ".$data['cp_period']."days", time()));
	}else{
		$cr_s_date = $data['cp_s_date'];
		$cr_e_date = $data['cp_e_date'];
	}

	$sql = "update rb_coupon_record set
				mb_id = '".$member['mb_id']."',
				cr_s_date = '$cr_s_date',
				cr_e_date = '$cr_e_date',
				cr_regdate = now()
				where cr_idx = '".$data['cr_idx']."'
			";
	$sql_q = sql_query($sql);

	alert("쿠폰이 발급되었습니다.", "./coupon_list.php?$query");


alert("잘못된 접근입니다.", "/");

include "../inc/_tail.php";
?>