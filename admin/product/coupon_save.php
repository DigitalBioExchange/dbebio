<?php
$menu_code = "400401";
$menu_mode = "w";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

//p_arr($_POST);exit;

$query = $_POST['query'];

$sql_common = "
	cp_title = '".$_POST['cp_title']."',
	cp_issue_type = '".$_POST['cp_issue_type']."',
	cp_issue_condition = '".$_POST['cp_issue_condition']."',
	cp_type = '".$_POST['cp_type']."',
	cp_amount = '".$_POST['cp_amount']."',
	cp_percent = '".$_POST['cp_percent']."',
	cp_min_amount = '".$_POST['cp_min_amount']."',
	cp_max_amount = '".$_POST['cp_max_amount']."',
	cp_period_type = '".$_POST['cp_period_type']."',
	cp_period = '".$_POST['cp_period']."',
	cp_s_date = '".$_POST['cp_s_date']."',
	cp_e_date = '".$_POST['cp_e_date']."',
	cp_product_type = '".$_POST['cp_product_type']."',
	cp_product = '".$_POST['cp_product']."',
	cp_cate_type = '".$_POST['cp_cate_type']."',
	cp_cate = '".$_POST['cp_cate']."',
	cp_min_pay_amount = '".$_POST['cp_min_pay_amount']."',
	cp_use = '".$_POST['cp_use']."'
";




if($_POST['mode'] == "insert"){

	$sql = "insert into rb_coupon set
				$sql_common
			";
	$sql_q = sql_query($sql);
	alert("쿠폰이 추가되었습니다.", "./coupon_list.php?$query");
}else if($_POST['mode'] == "update"){

	$data = sql_fetch("select * from rb_coupon as q where q.cp_idx = '$cp_idx' $search_query");

	if(!$data['cp_idx']) alert("없는 쿠폰입니다.");


	$sql = "update rb_coupon set
				$sql_common
				where cp_idx = '".$_POST['cp_idx']."'
			";
	$sql_q = sql_query($sql);
	$cp_idx = $_POST['cp_idx'];

	alert("쿠폰이 수정되었습니다.", "./coupon_view.php?cp_idx=".$_POST['cp_idx']."&$query");
}else if($_GET['mode'] == "delete"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];

	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_coupon as q where q.cp_idx = '$cp_idx' $search_query");

	if(!$data['cp_idx']) alert("없는 쿠폰입니다.");

	sql_query("delete from  rb_coupon  where cp_idx = '$cp_idx'");

	alert("쿠폰이 삭제되었습니다.", "./coupon_list.php?$query");

}else if($_POST['mode'] == "issue"){

	$data = sql_fetch("select * from rb_coupon as q where q.cp_idx = '$cp_idx' $search_query");

	if(!$data['cp_idx']) alert("없는 쿠폰입니다.");

	if($data['cp_period_type'] == '1'){
		$cr_s_date = date("Y-m-d");
		$cr_e_date = date("Y-m-d", strtotime("+ ".$data['cp_period']."days", time()));
	}else{
		$cr_s_date = $data['cp_s_date'];
		$cr_e_date = $data['cp_e_date'];
	}

	$sql = "insert into rb_coupon_record set
				cp_idx = '".$_POST['cp_idx']."',
				mb_id = '".$_POST['mb_id']."',
				cr_s_date = '$cr_s_date',
				cr_e_date = '$cr_e_date',
				cr_regdate = now()

			";
	$sql_q = sql_query($sql);

	alert("쿠폰이 발급되었습니다.", "./coupon_issue.php?$query");

}else if($_POST['mode'] == "issue2"){

	$data = sql_fetch("select * from rb_coupon as q where q.cp_idx = '$cp_idx' $search_query");

	if(!$data['cp_idx']) alert("없는 쿠폰입니다.");

	if($data['cp_period_type'] == '1'){
		$cr_s_date = date("Y-m-d");
		$cr_e_date = date("Y-m-d", strtotime("+ ".$data['cp_period']."days", time()));
	}else{
		$cr_s_date = $data['cp_s_date'];
		$cr_e_date = $data['cp_e_date'];
	}


	if($_POST['cc_cnt'] <= 0){
		alert("1장이상 발급하여야합니다.");
	}

	$sql = "insert into rb_coupon_cord_record set
				cp_idx = '".$_POST['cp_idx']."',
				cc_title = '".$_POST['cc_title']."',
				cc_cnt = '".$_POST['cc_cnt']."',
				cc_regdate = now()
			";
	$sql_q = sql_query($sql);
	$cc_idx = sql_insert_id();
	//$cc_idx = 1;

	//쿠폰추가
	$sql = "INSERT INTO rb_coupon_record (cp_idx, cc_idx, cr_code) VALUES";
	$sql_arr = array();
	for($i=0;$i<$_POST['cc_cnt'];$i++){
		$cr_code = make_cr_code($cc_idx, $i);
		$cr_regdate = date("Y-m-d H:i:s");
		$sql_arr[] = " (".$_POST['cp_idx'].", ".$cc_idx.", '".$cr_code."') ";
	}
	$sql .= implode(",", $sql_arr).";";
	sql_query($sql);


	alert("쿠폰이 발급되었습니다.", "./coupon_issue2.php?$query");

}else if($_GET['mode'] == "cr_delete"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "cp_idx=".$_GET['cp_idx'];
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];

	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_coupon_record as q where q.cr_idx = '$cr_idx' $search_query");

	if(!$data['cr_idx']) alert("없는 쿠폰입니다.");

	sql_query("delete from  rb_coupon_record  where cr_idx = '$cr_idx'");

	alert("쿠폰이 삭제되었습니다.", "./coupon_issue.php?$query");

}

alert("잘못된 접근입니다.", "./coupon_list.php?$query");
?>