<?php
$menu_code = "600100";
$menu_mode = "w";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

$query = $_POST['query'];


if($_POST['mode'] == "insert"){

	// $mb = get_member($_POST['mb_id']);
	// if(!$mb['mb_id']){
	// 	alert("없는 아이디입니다.");
	// }

	// write_member_point($mb['mb_id'], $_POST['ph_point'], $_POST['ph_memo']);


	// alert("포인트정보가 추가 되었습니다.", "./point_list.php?$query");

}else if($_GET['mode'] == "delete"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";


	$data = sql_fetch("select * from rb_product_review where pr_idx = '$pr_idx'");
	if(!$data['pr_idx']) alert("없는 리뷰정보입니다.");

	$sql = "select * from rb_product_review_file where pr_idx = '".$pr_idx."' ";
	$data = sql_list($sql);
	foreach ($data as $key => $value) {
		//파일삭제
		@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/".$value['fi_name']);
		@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/thumb_".$value['fi_name']);
		// @unlink($_cfg['web_home'].$_cfg['data_dir']."/files/thumb2_".$value['fi_name']);
	}
	sql_query("delete from rb_product_review_file where pr_idx = '".$_GET['pr_idx']."'");

	sql_query("delete from rb_product_review where pr_idx = '".$_GET['pr_idx']."'");

	alert("리뷰정보가 삭제되었습니다.", "./review_list.php?$query");

}

alert("잘못된 접근입니다.", "./review_list.php?$query");
?>