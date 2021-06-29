<?php
include "../inc/_common.php";
include "../inc/_head.php";

goto_login();

//p_arr($_POST);exit;

$query = $_POST[query];


$sql = "select * from rb_order where od_idx = '$_POST[od_idx]' and mb_id = '".$member[mb_id]."'";
$data = sql_fetch($sql);
if(!$data[od_idx]) alert("없는 주문입니다.");

$cart = sql_fetch("select * from rb_order_cart where od_idx = '$_POST[od_idx]' and ct_idx = '$_POST[ct_idx]' and ct_status in (4, 5)");
if(!$cart[ct_idx]) alert("없는 주문입니다.");

//리뷰가능여부
$chk = sql_fetch("select * from rb_product_review where mb_id = '$member[mb_id]' and ct_idx = '$_POST[ct_idx]'");
if($chk[pr_idx]){
	alert("이미 후기를 작성하였습니다.");
}

//이미지체크
$field_arr = array("pr_img");
foreach($field_arr as $k => $v){
	if($user_agent == "app"){
		if($_POST[$v]){
			$timg = @getimagesize($_cfg['web_home']."/data/tmp/".$_POST[$v]);
			if($timg[2] != 1 && $timg[2] != 2 && $timg[2] != 3) alert("jpg, gif, png 파일만 업로드 가능합니다.");
		}
	}else{
		if($_FILES[$v][tmp_name]){
			$timg = @getimagesize($_FILES[$v][tmp_name]);
			if($timg[2] != 1 && $timg[2] != 2 && $timg[2] != 3) alert("jpg, gif, png 파일만 업로드 가능합니다.");
		}
	}
}

$sql_common = "
	pd_idx = '".$cart[pd_idx]."',
	od_idx = '".$data[od_idx]."',
	ct_idx = '".$cart[ct_idx]."',

	mb_id = '".$member[mb_id]."',

	pr_title = '".$_POST[pr_title]."',
	pr_contents = '".$_POST[pr_contents]."',
	pr_point = '".$_POST[pr_point]."'
";

$sql = "insert into rb_product_review set
			$sql_common,
			pr_regdate = now()
		";
$sql_q = sql_query($sql);
$pr_idx = mysql_insert_id();

//이미지저장
foreach($field_arr as $k => $v){
	if($user_agent == "app"){
		if($_POST[$v]){
			$src = $_cfg['web_home']."/data/tmp/".$_POST[$v];
			$ext = strtolower(get_file_ext($_POST[$v]));
			$tgt_name = md5(uniqid(rand(), TRUE)).".".$ext;
			$tgt_name = preg_replace("/\.(php|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $tgt_name);
			$org_name = $_POST[$v."_org"];
			$tgt = $_cfg['web_home'].$_cfg['data_dir']."/files/".$tgt_name;

			Chk_exif_WH2($src, $tgt);

			sql_query("update rb_product_review set $v = '$tgt_name', {$v}_org = '$org_name' where pr_idx = '$pr_idx'");

		}
	}else{
		if($_FILES[$v][tmp_name]){
			$src = $_FILES[$v][tmp_name];
			$ext = strtolower(get_file_ext($_FILES[$v][name]));
			$tgt_name = md5(uniqid(rand(), TRUE)).".".$ext;
			$tgt_name = preg_replace("/\.(php|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $tgt_name);
			$org_name = $_FILES[$v][name];
			$tgt = $_cfg['web_home'].$_cfg['data_dir']."/files/".$tgt_name;

			Chk_exif_WH($src, $tgt);

			sql_query("update rb_product_review set $v = '$tgt_name', {$v}_org = '$org_name' where pr_idx = '$pr_idx'");

		}
	}
}

//평균과 카운트
set_product_review_result($cart[pd_idx]);

//쿠폰발급
$data = sql_list("select * from rb_coupon as q where q.cp_issue_type = '2' and q.cp_issue_condition = '2' and cp_use = '1'");
for($i=0;$i<count($data);$i++){

	if($data[$i][cp_period_type] == '1'){
		$cr_s_date = date("Y-m-d");
		$cr_e_date = date("Y-m-d", strtotime("+ ".$data[$i][cp_period]."days", time()));
	}else{
		$cr_s_date = $data[$i][cp_s_date];
		$cr_e_date = $data[$i][cp_e_date];
	}
	
	$cp_idx = $data[$i][cp_idx];

	$sql = "insert into rb_coupon_record set
				cp_idx = '$cp_idx',
				mb_id = '$mb_id',
				cr_s_date = '$cr_s_date',
				cr_e_date = '$cr_e_date',
				cr_regdate = now()

			";
	$sql_q = sql_query($sql);
}

alert("후기가 저장되었습니다.", "/shop/order_view.php?od_idx={$_POST[od_idx]}&".$query);

include "../inc/_tail.php";
?>