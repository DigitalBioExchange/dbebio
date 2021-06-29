<?php
$menu_code = "110300";
$menu_mode = "w";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

$query = $_POST['query'];


$sql_common = "
	ps_name = '".$_POST['ps_name']."',
	ps_status = '".$_POST['ps_status']."',
	ps_point = '".$_POST['ps_point']."'
";

if($_POST['mode'] == "insert"){

	$sql = "insert into rb_point_setting set
				$sql_common
			";
	$sql_q = sql_query($sql);
	$ps_idx = mysql_insert_id();

	alert("포인트 지급내용이 추가 되었습니다.", "./point_coin_list.php?$query");
}else if($_POST['mode'] == "update" && $_POST['ps_idx']){

	$sql = "update rb_point_setting set
				$sql_common
			where ps_idx = '".$_POST['ps_idx']."'
			";
	$sql_q = sql_query($sql);
	
	alert("포인트 지급내용이 수정 되었습니다.", "./point_coin_list.php?$query");
}else if($_GET['mode'] == "delete"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "ps_status=".$_GET['ps_status'];


	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	sql_query("delete from rb_point_setting where ps_idx = '$ps_idx'");


	alert("포인트 지급내용이 삭제 되었습니다.", "./point_coin_list.php?$query");
}

alert("잘못된 접근입니다.", "./point_coin_list.php?$query");
?>