<?php
$menu_code = "800040";
$menu_mode = "w";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

//p_arr($_POST);exit;

$query = $_POST['query'];

$sql_common = "
	pe_name = '".$_POST['pe_name']."',
	mb_id = '".$member['mb_id']."',
	pe_birth = '".$_POST['pe_birth']."',
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

//이미지체크
$field_arr = array("pe_img");
foreach($field_arr as $k => $v){
	if($_FILES[$v]['tmp_name']){
		$timg = @getimagesize($_FILES[$v]['tmp_name']);
		if($timg[2] != 1 && $timg[2] != 2 && $timg[2] != 3) alert("jpg, gif, png 파일만 업로드 가능합니다.");
	}
}
if($_POST['mode'] == "update"){

	$data = sql_fetch("select * from rb_pet as p where p.pe_idx = '$pe_idx' $search_query");

	if(!$data['pe_idx']) alert("없는 반려동물입니다.");


	$sql = "update rb_pet set
				$sql_common
				where pe_idx = '".$_POST['pe_idx']."'
			";
	$sql_q = sql_query($sql);
	$pe_idx = $_POST['pe_idx'];


	//파일저장
	foreach($field_arr as $k => $v){
		if($_FILES[$v]['tmp_name']){
			$src = $_FILES[$v]['tmp_name'];
			$ext = get_file_ext($_FILES[$v]['name']);
			$tgt_name = md5(uniqid(rand(), TRUE)).".".$ext;
			$org_name = $_FILES[$v]['name'];
			$tgt = $_cfg['web_home'].$_cfg['data_dir']."/files/".$tgt_name;

			Chk_exif_WH($src, $tgt);

			sql_query("update rb_pet set $v = '$tgt_name', {$v}_org = '$org_name' where pe_idx = '$pe_idx'");

		}
	}



	alert("반려동물이 수정되었습니다.", "./pet_view.php?pe_idx=".$_POST['pe_idx']."&$query");
}else if($_GET['mode'] == "delete"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_pet as p where p.pe_idx = '$pe_idx' $search_query");

	if(!$data['pe_idx']) alert("없는 반려동물입니다.");

	//파일삭제
	foreach($field_arr as $k => $v){
		@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/".$data[$v]);
	}


	sql_query("delete from  rb_pet  where pe_idx = '$pe_idx'");

	alert("반려동물이 삭제되었습니다.", "./pet_list.php?$query");

}

alert("잘못된 접근입니다.", "./pds_list.php?$query");
?>