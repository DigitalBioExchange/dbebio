<?php
$menu_code = "100300";
$menu_mode = "w";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

$query = $_POST['query'];


if($_POST['mode'] == "insert"){

	$mb = get_member($_POST['mb_id']);
	if(!$mb['mb_id']){
		alert("없는 아이디입니다.");
	}

	write_member_point($mb['mb_id'], $_POST['ph_point'], $_POST['ph_memo']);


	alert("포인트정보가 추가 되었습니다.", "./point_list.php?$query");

}else if($_GET['mode'] == "delete"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	$querys[] = "s_start=".$_GET['s_start'];
	$querys[] = "s_end=".$_GET['s_end'];
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";


	$data = sql_fetch("select * from rb_point_history where ph_idx = '$ph_idx'");
	if(!$data['ph_idx']) alert("없는 포인트정보입니다.");

	$ph_point = 0 - $data['ph_point'];

	sql_query("update rb_member set mb_point = mb_point + $ph_point where mb_id = '".$data['mb_id']."'");

	sql_query("delete from rb_point_history where ph_idx = '".$_GET['ph_idx']."'");

	alert("포인트정보가 삭제되었습니다.", "./point_list.php?$query");

}

alert("잘못된 접근입니다.", "./point_list.php?$query");
?>