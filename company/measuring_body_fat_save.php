<?php
include "../inc/_common.php";
include "../inc/_head.php";

if (!$is_member) {
	alert($_lang['inc']['text_0697']);
} else {
	if ($member['mb_birth'] && $member['mb_sex'] && $member['mb_height']) {
		$is_profile = true;
	} else {
		$is_profile = false;
	}

	if ($is_profile == false) {
		alert($_lang['health']['text_0799']);
	}
}

// p_arr($_POST);exit;

$query = $_POST['query'];

if($_POST['mode'] == "insert"){

	//BMI계산
	$BMI = $_POST['weight'] / (($member['mb_height'] / 100) * ($member['mb_height'] / 100));

	//body_fat_rate 체지방률계산
	if ($member['mb_sex'] == 1) {
		$body_fat_rate = (1.1 * $_POST['weight']) - (128 * ($_POST['weight'] / $member['mb_height']));
	} else if ($member['mb_sex'] == 2) {
		$body_fat_rate = (1.07 * $_POST['weight']) - (128 * ($_POST['weight'] / $member['mb_height']));
	}

	//BMR 기초대사량게산
	$age = cal_age($member['mb_birth']);
	if ($member['mb_sex'] == 1) {
		$BMR = 66.47 + (13.75 * $_POST['weight']) + (5 * $member['mb_height']) - (6.76 * $age);
	} else if ($member['mb_sex'] == 2) {
		$BMR = 655.1 + (9.56 * $_POST['weight']) + (1.85 * $member['mb_height']) - (4.68 * $age);
	}

	//입력날짜 계산
	$_POST['bf_regdate'] = $_POST['bf_regdate1']." ".$_POST['bf_regdate2'].":00";

	$sql = "insert into rb_body_fat set
						mb_idx = '".$member['mb_idx']."',
						mb_id = '".$member['mb_id']."',
						weight = '".$_POST['weight']."',
						BMI = '".$BMI."',
						body_fat_rate = '".$body_fat_rate."',
						body_water_rate = '".$_POST['body_water_rate']."',
						bone_mass = '".$_POST['bone_mass']."',
						BMR = '".$BMR."',
						visceral_fat = '".$_POST['visceral_fat']."',
						muscle_rate = '".$_POST['muscle_rate']."',
						metabolic_age = '".$_POST['metabolic_age']."',
						bf_regdate = '".$_POST['bf_regdate']."'
				";
	$sql_q = sql_query($sql);

	goto_url("/company/measurement_result_list.php");

}
alert($_lang['member']['text_0765']);
include "../inc/_tail.php";
?>