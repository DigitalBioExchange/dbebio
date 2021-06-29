<?php
$menu_code = "400312";
$menu_mode = "w";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

//p_arr($_POST);exit;

$query = $_POST['query'];

$sql_common = "
	qu_answer = '".$_POST['qu_answer']."',
	qu_answer_date = now()
";




if($_POST['mode'] == "update"){

	$data = sql_fetch("select * from rb_question as q where q.qu_idx = '$qu_idx' $search_query");

	if(!$data['qu_idx']) alert("없는 문의입니다.");


	$sql = "update rb_question set
				$sql_common
				where qu_idx = '".$_POST['qu_idx']."'
			";
	$sql_q = sql_query($sql);
	$qu_idx = $_POST['qu_idx'];

	alert("문의가 수정되었습니다.", "./qna2_view.php?qu_idx=".$_POST['qu_idx']."&$query");
}else if($_GET['mode'] == "delete"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_question as q where q.qu_idx = '$qu_idx' $search_query");

	if(!$data['qu_idx']) alert("없는 문의입니다.");

	sql_query("delete from  rb_question  where qu_idx = '$qu_idx'");

	alert("문의가 삭제되었습니다.", "./qna2_list.php?$query");

}

alert("잘못된 접근입니다.", "./qna2_list.php?$query");
?>