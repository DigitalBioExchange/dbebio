<?php
include "../inc/_common.php";
include "../inc/_head.php";

include "../_inc/_product_config.php";
$product_config = $_cfg['product_config'];

$query = $_POST[query];

if($_POST[mode] == "c_insert"){

	$data = sql_fetch("select * from rb_product as b where b.pd_idx = '$pd_idx' $search_query");
	if(!$data[pd_idx]) alert("없는 상품입니다.");

	if(!$product_config[is_comment]) alert("잘못된 접근입니다.");

	if($product_config[comment_insert_level] > $m_level){
		alert("권한이 없습니다.");
	}


	$sql = "insert into rb_product_comment set
				mb_id = '".$member[mb_id]."',
				pd_idx = '".$_POST[pd_idx]."',
				cm_contents = '".$_POST[cm_contents]."',
				cm_regdate = now()
			";
	$sql_q = sql_query($sql);

	alert("댓글이 추가되었습니다.", "/company/product_view.php?pd_idx=$_POST[pd_idx]&$query");

}else if($_POST[mode] == "c_update"){

	$data = sql_fetch("select * from rb_product as b where b.pd_idx = '$pd_idx' $search_query");
	if(!$data[pd_idx]) alert("없는 상품입니다.");

	if(!$product_config[is_comment]) alert("잘못된 접근입니다.");

	if($product_config[comment_insert_level] > $m_level){
		alert("권한이 없습니다.");
	}



	$data2 = sql_fetch("select * from rb_product_comment where pd_idx = '$pd_idx' and cm_idx = '$cm_idx'");
	if(!$data2[cm_idx]) alert("없는 댓글입니다.");

	if($data2[mb_id] != $member[mb_id]){
		alert("권한이 없습니다.");
	}

	$sql = "update rb_product_comment set
				cm_contents = '".$_POST[cm_contents]."'
				where cm_idx = '$cm_idx'
			";
	$sql_q = sql_query($sql);

	alert("댓글이 수정되었습니다.", "/company/product_view.php?pd_idx=$_POST[pd_idx]&$query");

}else if($_GET[mode] == "c_delete"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET[sca];
	$querys[] = "stx=".$_GET[stx];
	if(is_array($product_config[category])){
		if($_GET[pd_category]){
			$querys[] = "pd_category=".$_GET[pd_category];
		}
	}
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_product as b where b.pd_idx = '$pd_idx' $search_query");
	if(!$data[pd_idx]) alert("없는 상품입니다.");

	if(!$product_config[is_comment]) alert("잘못된 접근입니다.");

	if($product_config[comment_insert_level] > $m_level){
		alert("권한이 없습니다.");
	}

	$data2 = sql_fetch("select * from rb_product_comment where pd_idx = '$pd_idx' and cm_idx = '$cm_idx'");
	if(!$data2[cm_idx]) alert("없는 댓글입니다.");

	if($data2[mb_id] != $member[mb_id]){
		alert("권한이 없습니다.");
	}


	$sql = "delete from rb_product_comment where cm_idx = '$cm_idx'
			";
	$sql_q = sql_query($sql);

	sql_query("delete from  rb_product_comment_like  where cm_idx = '".$cm_idx."'");

	alert("댓글이 삭제되었습니다.", "/company/product_view.php?pd_idx=$_GET[pd_idx]&$query");

}
alert("잘못된 접근입니다.");
include "../inc/_tail.php";
?>