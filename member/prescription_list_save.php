<?php
include "../inc/_common.php";
include "../inc/_head.php";
goto_login();

$prescription = sql_fetch("select * from rb_prescription where vet_id = '".$member['mb_id']."' and pr_idx = '$pr_idx'");
if(!$prescription['pr_idx']){
	alert("처방식 구매 인증요청이 없습니다.");
}

if($prescription['pr_status'] != 1){
	alert("이미 승인/반려 처리하신 요청입니다.");
}

$pr_sdate = date("Y-m-d");
$pr_edate = date("Y-m-d", strtotime("+1 years", time()));
$sql_common = "
	pr_status = '".$_POST['pr_status']."',
	pr_sdate = '".$pr_sdate."',
	pr_edate = '".$pr_edate."'
";

$sql = "update rb_prescription set
			$sql_common
			where pr_idx = '$pr_idx'
		";
$sql_q = sql_query($sql);

if($pq_idx){
	$prescription_question = sql_fetch("select * from rb_prescription_question where pr_idx = '$pr_idx' and pq_idx = '$pq_idx'");
	if($prescription_question['pq_idx']){
		$sql = "update rb_prescription_question set
					pq_answer = '".$_POST['pq_answer']."', pq_answer_date = now()
					where pq_idx = '$pq_idx'
				";
		$sql_q = sql_query($sql);
	}
}

alert("처리되었습니다.", "/member/prescription_list.php?".$query);

include "../inc/_tail.php";
?>