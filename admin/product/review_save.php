<?php
$menu_code = "400302";
$menu_mode = "w";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

//p_arr($_POST);exit;

$query = $_POST['query'];
if($_GET['mode'] == "delete"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_product_review as q where q.pr_idx = '$pr_idx' $search_query");

	if(!$data['pr_idx']) alert("없는 리뷰입니다.");

	sql_query("delete from  rb_product_review  where pr_idx = '$pr_idx'");

	alert("리뷰가 삭제되었습니다.", "./review_list.php?$query");

}

alert("잘못된 접근입니다.", "./review_list.php?$query");
?>