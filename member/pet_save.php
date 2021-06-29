<?php
include "../inc/_common.php";
include "../inc/_head.php";
goto_login();


$sql_common = "
	pe_name = '".$_POST['pe_name']."',
	mb_id = '".$member['mb_id']."',
	pe_birth = '".$_POST['pe_birth_y']."-".$_POST['pe_birth_m']."-".$_POST['pe_birth_d']."',
	pe_type1 = '".$_POST['pe_type1']."',
	pe_type2 = '".$_POST['pe_type2']."',
	pe_allergy = '".$_POST['pe_allergy']."',

	pe_tag1 = '".$_POST['pe_tag1']."',
	pe_tag2 = '".$_POST['pe_tag2']."',
	pe_tag3 = '".$_POST['pe_tag3']."',
	pe_snack1 = '".$_POST['pe_snack1']."',
	pe_snack2 = '".$_POST['pe_snack2']."',
	pe_snack3 = '".$_POST['pe_snack3']."'
";

//p_arr($_POST);echo "ok";exit;

$field_arr = array("pe_img");

if($_POST['mode'] == "insert"){
	

	$sql = "insert into rb_pet set
				$sql_common
			";
	$sql_q = sql_query($sql);
	$pe_idx = sql_insert_id();


	//이미지저장
	foreach($field_arr as $k => $v){
		if($_POST[$v]){
			$org_name = $_POST[$v."_org"];
			$src = $_cfg['web_home'].$_cfg['data_dir']."/tmp/".$_POST[$v];
			$tgt = $_cfg['web_home'].$_cfg['data_dir']."/files/".$_POST[$v];
			$tgt_name = $_POST[$v];

			@copy($src, $tgt);
			@unlink($src);

			sql_query("update rb_pet set $v = '$tgt_name', {$v}_org = '$org_name' where pe_idx = '$pe_idx'");
		}
	}

	goto_url("/member/pet_list.php");
}else if($_POST['mode'] == "update"){
	
	$data = sql_fetch("select * from rb_pet where mb_id = '".$member['mb_id']."' and pe_idx = '$pe_idx'");
	if(!$data['pe_idx']){
		alert("없는 반려동물입니다.");
	}

	$sql = "update rb_pet set
				$sql_common				
				where pe_idx = '".$pe_idx."'
			";
	$sql_q = sql_query($sql);


	//파일삭제
	foreach($field_arr as $k => $v){
		if($_POST[$v] != "" && $data[$v] != ""){
			@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/".$data[$v]);
		}
	}

	//이미지저장
	foreach($field_arr as $k => $v){
		if($_POST[$v]){
			$org_name = $_POST[$v."_org"];
			$src = $_cfg['web_home'].$_cfg['data_dir']."/tmp/".$_POST[$v];
			$tgt = $_cfg['web_home'].$_cfg['data_dir']."/files/".$_POST[$v];
			$tgt_name = $_POST[$v];

			@copy($src, $tgt);
			@unlink($src);

			sql_query("update rb_pet set $v = '$tgt_name', {$v}_org = '$org_name' where pe_idx = '$pe_idx'");
		}
	}

	alert("수정되었습니다.", "/member/pet_list.php?pe_idx=".$pe_idx);
}else if($_GET['mode'] == "delete"){
	$data = sql_fetch("select * from rb_pet where mb_id = '".$member['mb_id']."' and pe_idx = '$pe_idx'");
	if(!$data['pe_idx']){
		alert("없는 반려동물입니다.");
	}

	$sql = "delete from rb_pet where pe_idx = '".$pe_idx."'	";
	$sql_q = sql_query($sql);


	//파일삭제
	foreach($field_arr as $k => $v){
		if($data[$v] != ""){
			@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/".$data[$v]);
		}
	}

	//처방인증 삭제
	$prescription = sql_list("select * from rb_prescription where pe_idx = '".$pe_idx."' ");
	sql_query("delete from rb_prescription where pe_idx = '".$pe_idx."'	" );
	$field1_arr = array("pr_img1", "pr_img2", "pr_img3");

	for($i=0;$i<custom_count($prescription);$i++){
		$pr_idx = $prescription[$i]['pr_idx'];
		foreach($field1_arr as $k => $v){
			if($prescription[$i][$v] != ""){
				@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/".$prescription[$i][$v]);
			}
		}

		$prescription_question = sql_list("select * from rb_prescription_question pe_idx = '".$pr_idx."'	 ");
		sql_query("delete from rb_prescription_question where pr_idx = '".$pr_idx."'	" );

		$field2_arr = array("pq_img");
		for($j=0;$j<custom_count($prescription_question);$j++){
			foreach($field2_arr as $k => $v){
				if($prescription_question[$j][$v] != ""){
					@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/".$prescription_question[$j][$v]);
				}
			}
		}
	}

	alert("삭제되었습니다.", "/member/pet_list.php");

}
	
//p_arr($_POST);echo "error";exit;

alert("잘못된 접근입니다.", "/");

include "../inc/_tail.php";
?>