<?php
include "../inc/_common.php";
include "../inc/_head.php";
//p_arr($_POST);exit;

if($_GET[mode] == "delete"){
	$where_query = ($is_member) ? "and mb_id = '".$member[mb_id]."'" : "and mb_session = '".session_id()."'";
	sql_query("delete from rb_cart where ct_idx = '$_GET[ct_idx]' $where_query");
	alert("삭제되었습니다.", "/shop/cart.php");
}else if($_POST[mode] == "delete_all"){
	$where_query = ($is_member) ? "and mb_id = '".$member[mb_id]."'" : "and mb_session = '".session_id()."'";
	sql_query("delete from rb_cart where 1 $where_query");
	alert("삭제되었습니다.", "/shop/cart.php");
}else if($_POST[mode] == "delete_selected"){
	$where_query = ($is_member) ? "and mb_id = '".$member[mb_id]."'" : "and mb_session = '".session_id()."'";

	for($i=0;$i<count($_POST[ct_idx]);$i++){
		$ct_idx = $_POST[ct_idx][$i];
		sql_query("delete from rb_cart where ct_idx = '$ct_idx' $where_query");
	}
	alert("삭제되었습니다.", "/shop/cart.php");
}
	


alert("잘못된 접근입니다.", "/");

include "../inc/_tail.php";
?>