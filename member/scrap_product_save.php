<?php
include "../inc/_common.php";
include "../inc/_head.php";

$query = $_POST['query'];

goto_login();

for($i=0;$i<custom_count($_POST['sc_idx']);$i++){
	$sc_idx = $_POST['sc_idx'][$i];

	$sql = "select * from rb_product_scrap where sc_idx = '$sc_idx'  and mb_id = '".$member['mb_id']."' ";
	$data = sql_fetch($sql);
	if(!$data['sc_idx']){
		alert("없는 스크랩입니다.");
	}

	sql_query("delete from rb_product_scrap where sc_idx = '$sc_idx' ");
	$scrap_cnt = sql_total("select * from rb_product_scrap where pd_idx = '$pd_idx'");
	sql_query("update rb_product set pd_scrap_cnt = $scrap_cnt where pd_idx = '{$pd_idx}'");
}



alert("삭제되었습니다.", "/member/scrap_product.php?".$query);

include "../inc/_tail.php";
?>