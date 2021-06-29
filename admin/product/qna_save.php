<?php
$menu_code = "400301";
$menu_mode = "w";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

//p_arr($_POST);exit;

$query = $_POST['query'];

$sql_common = "
	pq_answer = '".$_POST['pq_answer']."',
	pq_answer_date = now()
";




if($_POST['mode'] == "update"){

	$data = sql_fetch("select * from rb_product_qna as q where q.pq_idx = '$pq_idx' $search_query");

	if(!$data['pq_idx']) alert("없는 상품문의입니다.");


	$sql = "update rb_product_qna set
				$sql_common
				where pq_idx = '".$_POST['pq_idx']."'
			";
	$sql_q = sql_query($sql);
	$pq_idx = $_POST['pq_idx'];

	alert("상품문의가 수정되었습니다.", "./qna_view.php?pq_idx=".$_POST['pq_idx']."&$query");
}else if($_GET['mode'] == "delete"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_product_qna as q where q.pq_idx = '$pq_idx' $search_query");

	if(!$data['pq_idx']) alert("없는 상품문의입니다.");

	sql_query("delete from  rb_product_qna  where pq_idx = '$pq_idx'");

	alert("상품문의가 삭제되었습니다.", "./qna_list.php?$query");

}

alert("잘못된 접근입니다.", "./qna_list.php?$query");
?>