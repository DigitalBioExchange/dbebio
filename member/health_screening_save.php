<?php
include "../inc/_common.php";
include "../inc/_head.php";
// p_arr($_POST); exit;

// $_POST['mb_hp'] = split_tel_number($_POST['mb_hp']);

$sql_common = "
	hs_date = '".$_POST['hs_date']."',
	hs_hospital = '".$_POST['hs_hospital']."',
	hs_heigh = '".$_POST['hs_heigh']."',
	hs_weight = '".$_POST['hs_weight']."',
	hs_waist = '".$_POST['hs_waist']."',
	hs_waist_type = '".$_POST['hs_waist_type']."',
	hs_bmi = '".$_POST['hs_bmi']."',
	hs_blood_high = '".$_POST['hs_blood_high']."',
	hs_blood_low = '".$_POST['hs_blood_low']."',
	hs_blood_sugar = '".$_POST['hs_blood_sugar']."',
	hs_cholesterol_t = '".$_POST['hs_cholesterol_t']."',
	hs_cholesterol_l = '".$_POST['hs_cholesterol_l']."',
	hs_fat = '".$_POST['hs_fat']."',
	hs_cholesterol_h = '".$_POST['hs_cholesterol_h']."',
	hs_urine = '".$_POST['hs_urine']."',
	hs_hemoglobin = '".$_POST['hs_hemoglobin']."',
	hs_serum_c = '".$_POST['hs_serum_c']."',
	hs_filtration_rate = '".$_POST['hs_filtration_rate']."',
	hs_ast = '".$_POST['hs_ast']."',
	hs_alt = '".$_POST['hs_alt']."',
	hs_gpt = '".$_POST['hs_gpt']."'
	
";

//이미지체크
for($i=0;$i<$_POST['file_cnt_total'];$i++){
	if($_POST["bd_file_" . $i]){
		$timg = @getimagesize($_cfg['web_home']."/data/tmp/".$_POST["bd_file_" . $i]);
		if($timg[2] != 1 && $timg[2] != 2 && $timg[2] != 3) alert($_lang['board']['text_0665']);
	}
}



if($_POST['mode'] == "insert"){
	

	$sql = "insert into rb_health_screening set
				$sql_common,
				mb_idx = '".$member['mb_idx']."',
				mb_id = '".$member['mb_id']."',
				hs_regdate = now()
			";
	$sql_q = sql_query($sql);
	$hs_idx = mysql_insert_id();


	//파일저장 => 멀티업로드 버전
	$num = 0;
	for($i=0;$i<$_POST['file_cnt_total'];$i++){
		
			if($_POST["bd_file_" . $i]){
				$src = $_cfg['web_home']."/data/tmp/".$_POST["bd_file_" . $i];
				$ext = strtolower(get_file_ext($_POST["bd_file_" . $i]));
				$tgt_name = md5(uniqid(rand(), TRUE)).".".$ext;
				$tgt_name = preg_replace("/\.(php|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $tgt_name);
				$org_name = $_POST["bd_file_" . $i."_org"];
				$tgt = $_cfg['web_home'].$_cfg['data_dir']."/files/".$tgt_name;
				$fi_size = filesize($src);

				Chk_exif_WH2($src, $tgt);
				
				$thumb = $_cfg['web_home'].$_cfg['data_dir']."/files/thumb_".$tgt_name;
				put_gdimage($tgt, 240, 0, $thumb);

				sql_query("insert into rb_health_file set fi_num = '$num', hs_idx = '$hs_idx', fi_name = '$tgt_name', fi_name_org = '$org_name', fi_size = '$fi_size', fi_regdate = now() ");
				$num++;

			}
		
	}

	//파일카운트
	$hs_file_cnt = sql_total("select * from rb_health_file where hs_idx = '$hs_idx'");
	sql_query("update rb_health_screening set hs_file_cnt = '$hs_file_cnt' where hs_idx = '$hs_idx'");

	//건강검징정보 월 1회 등록보상
	$sql = "select * from rb_point_setting where ps_idx = 3";
	$data_chk = sql_fetch($sql);
	if ($data_chk['ps_status'] == 1) {
		$sql_ph = "select * from rb_point_history where mb_id = '".$member['mb_id']."' and ph_type = '".$data_chk['ps_idx']."' and date_format(ph_regdate, '%Y-%m') = '".date('Y-m')."' ";
		$data_ph = sql_fetch($sql_ph);
		if (!$data_ph['ph_idx']) {
			$msg_ko = "건강검진정보 월 1회 등록 보상 지급";
			$msg_en = "Health check-up information once a month registration compensation payment";
			write_member_point($member['mb_id'], $data_chk['ps_point'], $data_chk['ps_idx'], $msg_ko, $msg_en);
		}
		
	}

	if ($user_agent == 'app') {
		?>
		<script>
			app_self_close();
		</script>
		<?
		exit;			
	} else {
		goto_url("/member/menu.php");
	}

}else if($_POST['mode'] == "update"){
	exit;
	goto_login();

	$pw_chk = sql_fetch("select * from rb_member where mb_id = '".$mb_id."' and mb_pass = sha2('".$_POST['mb_pass']."', 256)");
	if(!$pw_chk['mb_idx']){
		alert($_lang['inc']['text_0709']);
	}

	$hp_chk = sql_fetch("select * from rb_member where mb_hp = '".$_POST['mb_hp']."' and mb_n_telnum = '".$_POST['mb_n_telnum']."' and mb_status = 1 and mb_idx != '".$member['mb_idx']."' ");
	if($hp_chk['mb_idx']){
		alert($_lang['member']['text_0788']);
	}

	$mb_idx = $pw_chk['mb_idx'];

	if($_POST['mb_pass_new']){
		$sql_common .= " , mb_pass = sha2('".$_POST['mb_pass_new']."', 256) ";
	}

	$sql = "update rb_member set
				$sql_common
				
				where mb_idx = '".$mb_idx."'
			";
	$sql_q = sql_query($sql);


	//파일삭제
	foreach($field_arr as $k => $v){
		if($_POST[$v."_del"] == 1){
			sql_query("update rb_member set $v = '', {$v}_org = '' where mb_idx = '$mb_idx'");
			@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/".$member[$v]);
		}
	}

	//이미지저장
	foreach($field_arr as $k => $v){
		if($user_agent == "app"){
			if($_POST[$v]){
				$src = $_cfg['web_home']."/data/tmp/".$_POST[$v];
				$ext = strtolower(get_file_ext($_POST[$v]));
				$tgt_name = md5(uniqid(rand(), TRUE)).".".$ext;
				$tgt_name = preg_replace("/\.(php|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $tgt_name);
				$org_name = $_POST[$v."_org"];
				$tgt = $_cfg['web_home'].$_cfg['data_dir']."/files/".$tgt_name;

				Chk_exif_WH2($src, $tgt);

				sql_query("update rb_member set $v = '$tgt_name', {$v}_org = '$org_name' where mb_idx = '$mb_idx'");

			}
		}else{
			if($_FILES[$v]['tmp_name']){
				$src = $_FILES[$v]['tmp_name'];
				$ext = strtolower(get_file_ext($_FILES[$v]['name']));
				$tgt_name = md5(uniqid(rand(), TRUE)).".".$ext;
				$tgt_name = preg_replace("/\.(php|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $tgt_name);
				$org_name = $_FILES[$v]['name'];
				$tgt = $_cfg['web_home'].$_cfg['data_dir']."/files/".$tgt_name;

				Chk_exif_WH($src, $tgt);

				sql_query("update rb_member set $v = '$tgt_name', {$v}_org = '$org_name' where mb_idx = '$mb_idx'");

			}
		}
	}

	if ($user_agent == 'app') {
		?>
		<script>
			alert("<?=$_lang['member']['text_0763']?>");
			app_self_close();
		</script>
		<?
		exit;			
	} else {
		alert($_lang['member']['text_0763'], "/member/menu.php");
	}

}else if($_GET['mode'] == "delete"){
	
	$sql = "select * from rb_health_screening where hs_idx = '".$hs_idx."' and mb_idx = '".$member['mb_idx']."' ";
	$data = sql_fetch($sql);

	if($data['hs_idx']){
		//파일삭제
		$sql_file = "select * from rb_health_file where hs_idx = '".$data['hs_idx']."' ";
		$field_arr = sql_list($sql_file);
		foreach($field_arr as $k => $v){
			@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/".$v['fi_name']);
			@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/thumb_".$v['fi_name']);
		}

		$sql_del = "delete from rb_health_screening where hs_idx = '".$data['hs_idx']."' ";
		sql_query($sql_del);

		alert($_lang['member']['text_0779'], "/member/health_screening_history.php");
		
	} else {
		alert($_lang['inc']['text_0709']);
	}

}
	
//p_arr($_POST);echo "error";exit;

alert($_lang['member']['text_0765'], "/");

include "../inc/_tail.php";
?>