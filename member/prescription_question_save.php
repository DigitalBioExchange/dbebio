<?php
include "../inc/_common.php";
include "../inc/_head.php";
goto_login();

$prescription = sql_fetch("select * from rb_prescription where mb_id = '".$member['mb_id']."' and pr_idx = '$pr_idx'");
if(!$prescription['pr_idx']){
	alert("처방식 구매 인증요청이 없습니다.");
}

$prescription_question = sql_fetch("select * from rb_prescription_question where pr_idx = '$pr_idx'");
if($prescription_question['pq_idx']){
	alert("이미 문의를 하셨습니다.");
}


$sql_common = "
	pr_idx = '".$_POST['pr_idx']."',
	mb_id = '".$member['mb_id']."',
	vet_id = '".$_POST['vet_id']."',
	pq_contents = '".$_POST['pq_contents']."'
";

//p_arr($_POST);echo "ok";exit;

$field_arr = array("pq_img");

if($_POST['mode'] == "insert"){
	

	$sql = "insert into rb_prescription_question set
				$sql_common,
				pq_regdate = now()
			";
	$sql_q = sql_query($sql);
	$pq_idx = sql_insert_id();


	//이미지저장
	foreach($field_arr as $k => $v){
		if($_POST[$v]){
			$org_name = $_POST[$v."_org"];
			$src = $_cfg['web_home'].$_cfg['data_dir']."/tmp/".$_POST[$v];
			$tgt = $_cfg['web_home'].$_cfg['data_dir']."/files/".$_POST[$v];
			$tgt_name = $_POST[$v];

			@copy($src, $tgt);
			@unlink($src);

			sql_query("update rb_prescription_question set $v = '$tgt_name', {$v}_org = '$org_name' where pq_idx = '$pq_idx'");
		}
	}

	alert("문의되었습니다.", "/member/prescription_question_insert.php?pr_idx=".$prescription['pr_idx']);
}
	
//p_arr($_POST);echo "error";exit;

alert("잘못된 접근입니다.", "/");

include "../inc/_tail.php";
?>