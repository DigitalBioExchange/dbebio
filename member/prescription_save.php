<?php
include "../inc/_common.php";
include "../inc/_head.php";
goto_login();


//p_arr($_POST);echo "ok";exit;

$field_arr = array("pr_img1", "pr_img2", "pr_img3");
$has_img = 0;
foreach($field_arr as $k => $v){
	if($_POST[$v]){
		$has_img = 1;
	}
}

if(!$_POST['pr_type']){
	$_POST['pr_type'] = 1;
	if($has_img > 0){
		$_POST['pr_type'] = 2;
	}
}

$sql_common = "
	pe_idx = '".$_POST['pe_idx']."',
	mb_id = '".$member['mb_id']."',
	vet_id = '".$_POST['vet_id']."',
	pr_type = '".$_POST['pr_type']."'
";

if($_POST['mode'] == "insert"){
	

	$sql = "insert into rb_prescription set
				$sql_common,
				pr_regdate = now()
			";
	$sql_q = sql_query($sql);
	$pr_idx = sql_insert_id();


	//이미지저장
	foreach($field_arr as $k => $v){
		if($_POST[$v]){
			$org_name = $_POST[$v."_org"];
			$src = $_cfg['web_home'].$_cfg['data_dir']."/tmp/".$_POST[$v];
			$tgt = $_cfg['web_home'].$_cfg['data_dir']."/files/".$_POST[$v];
			$tgt_name = $_POST[$v];

			@copy($src, $tgt);
			@unlink($src);

			sql_query("update rb_prescription set $v = '$tgt_name', {$v}_org = '$org_name' where pr_idx = '$pr_idx'");
		}
	}

	goto_url("/member/prescription.php?pe_idx=".$_POST['pe_idx']);
}
	
//p_arr($_POST);echo "error";exit;

alert("잘못된 접근입니다.", "/");

include "../inc/_tail.php";
?>