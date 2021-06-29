<?php
$page_name = "index";
$page_option = "index";
$logo = "active";
$back = "not";


include "./inc/_common.php";
$is_index = 1;

//$is_sub_admin_page = 1;
if ($user_agent != "app") {
	$gnb = "1";
	goto_login();
}

$t_menu = 0;
$l_menu = 0;


$tpl=new Template;
$tpl->define(array(
	'contents'  =>'index.tpl',
	'left'  =>	'inc/member_left.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
));

//팝업
$popup_data = sql_list("select * from rb_popup where pp_agent = '".$user_agent."' and pp_use = 1 ");
for($i=0;$i<custom_count($popup_data);$i++){
	if( $_COOKIE['popup_'.$popup_data[$i]['pp_idx']] != "done"){
		$popup_data[$i]['pp_view'] = 1;
	}else{
		$popup_data[$i]['pp_view'] = 0;
	}
}
$tpl->assign('popup_data', $popup_data);

if($user_agent == "web"){
	$banner_basic = 100;
}else if($user_agent == "mobile"){
	$banner_basic = 300;
}else if($user_agent == "app"){
	$banner_basic = 600;
}
//$banner_basic = 0;
//스크롤배너
$banner1 = sql_list("select * from rb_banner where bn_loc = '".(1 + $banner_basic)."' order by bn_sort desc");
$tpl->assign('banner1', $banner1);

//메인중단
$banner2 = sql_list("select * from rb_banner where bn_loc = '".(2 + $banner_basic)."' order by bn_sort desc");
$tpl->assign('banner2', $banner2);

//최상단 folding-banner
$banner3 = sql_list("select * from rb_banner where bn_loc = '".(3 + $banner_basic)."' order by bn_sort desc limit 0, 1");
$tpl->assign('banner3', $banner3);
//p_arr($banner3);

if ($_GET['view_type']) {
	$view_type = $_GET['view_type'];
} else {
	$view_type = '';
}

if ($is_member) {

	//오늘 데이터 유무
	$sql_today = "select * from rb_body_fat where mb_idx = '".$member['mb_idx']."' and bf_regdate > CURRENT_DATE() order by bf_regdate desc";
	$data_today = sql_fetch($sql_today);
	if ($data_today['bf_idx']) {
		$tpl->assign('data_today', $data_today);

		//BMI판정기준
		if ($data_today['BMI'] < 18.5) {
			$bmi_result = $_lang['tpl_index']['text_0005'];
			$bmi_num = 1;
		} else if ($data_today['BMI'] >= 18.5 && $data_today['BMI'] < 23) {
			$bmi_result = $_lang['tpl_index']['text_0006'];
			$bmi_num = 2;
		} else if ($data_today['BMI'] >= 23 && $data_today['BMI'] < 25) {
			$bmi_result = $_lang['tpl_index']['text_0007'];
			$bmi_num = 1;
		} else if ($data_today['BMI'] >= 25) {
			$bmi_result = $_lang['tpl_index']['text_0008'];
			$bmi_num = 3;
		}
		$tpl->assign('bmi_result', $bmi_result);
		$tpl->assign('bmi_num', $bmi_num);

		//Body_fat판정기준
		$age = cal_age($member['mb_birth']);
		if ($member['mb_sex'] == 1) {
			if ($age < 40) {
				if ($data_today['body_fat_rate'] < 8) {
					$body_fat_result = $_lang['tpl_index']['text_0005'];
					$body_fat_num = 1;
				} else if ($data_today['body_fat_rate'] >= 8 && $data_today['body_fat_rate'] < 20) {
					$body_fat_result = $_lang['tpl_index']['text_0006'];
					$body_fat_num = 2;
				} else if ($data_today['body_fat_rate'] >= 20 && $data_today['body_fat_rate'] < 26) {
					$body_fat_result = $_lang['tpl_index']['text_0007'];
					$body_fat_num = 1;
				} else if ($data_today['body_fat_rate'] >= 26) {
					$body_fat_result = $_lang['tpl_index']['text_0008'];
					$body_fat_num = 3;
				}

			} else if ($age >= 40 && $age < 60) {
				if ($data_today['body_fat_rate'] < 11) {
					$body_fat_result = $_lang['tpl_index']['text_0005'];
					$body_fat_num = 1;
				} else if ($data_today['body_fat_rate'] >= 11 && $data_today['body_fat_rate'] < 22) {
					$body_fat_result = $_lang['tpl_index']['text_0006'];
					$body_fat_num = 2;
				} else if ($data_today['body_fat_rate'] >= 22 && $data_today['body_fat_rate'] < 29) {
					$body_fat_result = $_lang['tpl_index']['text_0007'];
					$body_fat_num = 1;
				} else if ($data_today['body_fat_rate'] >= 29) {
					$body_fat_result = $_lang['tpl_index']['text_0008'];
					$body_fat_num = 3;
				}

			} else if ($age >= 60) {
				if ($data_today['body_fat_rate'] < 13) {
					$body_fat_result = $_lang['tpl_index']['text_0005'];
					$body_fat_num = 1;
				} else if ($data_today['body_fat_rate'] >= 13 && $data_today['body_fat_rate'] < 25) {
					$body_fat_result = $_lang['tpl_index']['text_0006'];
					$body_fat_num = 2;
				} else if ($data_today['body_fat_rate'] >= 25 && $data_today['body_fat_rate'] < 31) {
					$body_fat_result = $_lang['tpl_index']['text_0007'];
					$body_fat_num = 1;
				} else if ($data_today['body_fat_rate'] >= 31) {
					$body_fat_result = $_lang['tpl_index']['text_0008'];
					$body_fat_num = 3;
				}

			}
		} else if ($member['mb_sex'] == 2) {
			if ($age < 40) {
				if ($data_today['body_fat_rate'] < 20) {
					$body_fat_result = $_lang['tpl_index']['text_0005'];
					$body_fat_num = 1;
				} else if ($data_today['body_fat_rate'] >= 20 && $data_today['body_fat_rate'] < 33) {
					$body_fat_result = $_lang['tpl_index']['text_0006'];
					$body_fat_num = 2;
				} else if ($data_today['body_fat_rate'] >= 33 && $data_today['body_fat_rate'] < 40) {
					$body_fat_result = $_lang['tpl_index']['text_0007'];
					$body_fat_num = 1;
				} else if ($data_today['body_fat_rate'] >= 40) {
					$body_fat_result = $_lang['tpl_index']['text_0008'];
					$body_fat_num = 3;
				}

			} else if ($age >= 40 && $age < 60) {
				if ($data_today['body_fat_rate'] < 22) {
					$body_fat_result = $_lang['tpl_index']['text_0005'];
					$body_fat_num = 1;
				} else if ($data_today['body_fat_rate'] >= 22 && $data_today['body_fat_rate'] < 34) {
					$body_fat_result = $_lang['tpl_index']['text_0006'];
					$body_fat_num = 2;
				} else if ($data_today['body_fat_rate'] >= 34 && $data_today['body_fat_rate'] < 41) {
					$body_fat_result = $_lang['tpl_index']['text_0007'];
					$body_fat_num = 1;
				} else if ($data_today['body_fat_rate'] >= 41) {
					$body_fat_result = $_lang['tpl_index']['text_0008'];
					$body_fat_num = 3;
				}

			} else if ($age >= 60) {
				if ($data_today['body_fat_rate'] < 23) {
					$body_fat_result = $_lang['tpl_index']['text_0005'];
					$body_fat_num = 1;
				} else if ($data_today['body_fat_rate'] >= 23 && $data_today['body_fat_rate'] < 36) {
					$body_fat_result = $_lang['tpl_index']['text_0006'];
					$body_fat_num = 2;
				} else if ($data_today['body_fat_rate'] >= 36 && $data_today['body_fat_rate'] < 43) {
					$body_fat_result = $_lang['tpl_index']['text_0007'];
					$body_fat_num = 1;
				} else if ($data_today['body_fat_rate'] >= 43) {
					$body_fat_result = $_lang['tpl_index']['text_0008'];
					$body_fat_num = 3;
				}

			}
		}
		$tpl->assign('body_fat_result', $body_fat_result);
		$tpl->assign('body_fat_num', $body_fat_num);

		//BMR판정기준
		if ($member['mb_sex'] == 1) {
			if ($age < 30) {
				if ($data_today['BMR'] < 1360) {
					$bmr_result = $_lang['tpl_index']['text_0009'];
					$bmr_num = 1;
				} else if ($data_today['BMR'] >= 1360 && $data_today['BMR'] < 2150) {
					$bmr_result = $_lang['tpl_index']['text_0006'];
					$bmr_num = 2;
				} else if ($data_today['BMR'] >= 2150) {
					$bmr_result = $_lang['tpl_index']['text_0010'];
					$bmr_num = 3;
				}

			} else if ($age >= 30 && $age < 50) {
				if ($data_today['BMR'] < 1367) {
					$bmr_result = $_lang['tpl_index']['text_0009'];
					$bmr_num = 1;
				} else if ($data_today['BMR'] >= 1367 && $data_today['BMR'] < 1971) {
					$bmr_result = $_lang['tpl_index']['text_0006'];
					$bmr_num = 2;
				} else if ($data_today['BMR'] >= 1971) {
					$bmr_result = $_lang['tpl_index']['text_0010'];
					$bmr_num = 3;
				}

			} else if ($age >= 50) {
				if ($data_today['BMR'] < 1178) {
					$bmr_result = $_lang['tpl_index']['text_0009'];
					$bmr_num = 1;
				} else if ($data_today['BMR'] >= 1178 && $data_today['BMR'] < 1808) {
					$bmr_result = $_lang['tpl_index']['text_0006'];
					$bmr_num = 2;
				} else if ($data_today['BMR'] >= 1808) {
					$bmr_result = $_lang['tpl_index']['text_0010'];
					$bmr_num = 3;
				}

			}
		} else if ($member['mb_sex'] == 2) {
			if ($age < 30) {
				if ($data_today['BMR'] < 1078) {
					$bmr_result = $_lang['tpl_index']['text_0009'];
					$bmr_num = 1;
				} else if ($data_today['BMR'] >= 1078 && $data_today['BMR'] < 1544) {
					$bmr_result = $_lang['tpl_index']['text_0006'];
					$bmr_num = 2;
				} else if ($data_today['BMR'] >= 1544) {
					$bmr_result = $_lang['tpl_index']['text_0010'];
					$bmr_num = 3;
				}

			} else if ($age >= 30 && $age < 50) {
				if ($data_today['BMR'] < 1090) {
					$bmr_result = $_lang['tpl_index']['text_0009'];
					$bmr_num = 1;
				} else if ($data_today['BMR'] >= 1090 && $data_today['BMR'] < 1541) {
					$bmr_result = $_lang['tpl_index']['text_0006'];
					$bmr_num = 2;
				} else if ($data_today['BMR'] >= 1541) {
					$bmr_result = $_lang['tpl_index']['text_0010'];
					$bmr_num = 3;
				}

			} else if ($age >= 50) {
				if ($data_today['BMR'] < 1024) {
					$bmr_result = $_lang['tpl_index']['text_0009'];
					$bmr_num = 1;
				} else if ($data_today['BMR'] >= 1024 && $data_today['BMR'] < 1480) {
					$bmr_result = $_lang['tpl_index']['text_0006'];
					$bmr_num = 2;
				} else if ($data_today['BMR'] >= 1480) {
					$bmr_result = $_lang['tpl_index']['text_0010'];
					$bmr_num = 3;
				}

			}
		}
		$tpl->assign('bmr_result', $bmr_result);
		$tpl->assign('bmr_num', $bmr_num);

		$sql_gap = "select * from rb_body_fat where mb_idx = '".$member['mb_idx']."' order by bf_idx desc limit 0, 2";
		$data_gap = sql_list($sql_gap);
		$weight_gap = $data_gap[0]['weight'] - $data_gap[1]['weight'];
		$rate_gap = $data_gap[0]['body_fat_rate'] - $data_gap[1]['body_fat_rate'];
		$bmi_gap = $data_gap[0]['BMI'] - $data_gap[1]['BMI'];

		$tpl->assign('weight_gap', round($weight_gap, 2));
		$tpl->assign('rate_gap', round($rate_gap, 2));
		$tpl->assign('bmi_gap', round($bmi_gap, 2));
	}


}

if ($view_type == 'week') {
	//일주일치 정보
	$day_of_week = date('w');
	$today = date('Y-m-d');
	$start_day = date('Y-m-d', strtotime($today." -".$day_of_week."days"));
	$end_day = date('Y-m-d', strtotime($start_day."+6 days"));
	$sql_week = "select cd.cd_symd, cd.cd_sd, ifnull (AVG(bf.weight), 0) as weight_avg, ifnull (AVG(bf.body_fat_rate), 0) as body_fat_rate_avg, ifnull (AVG(bf.BMI), 0) as bmi_avg, ifnull (AVG(bf.body_water_rate), 0) as body_water_rate_avg, cd.cd_symd as bf_regdate
								from rb_calendar_data as cd 
								left join (select * from rb_body_fat where mb_idx = '".$member['mb_idx']."' ) as bf on DATE_FORMAT(bf.bf_regdate, '%Y-%m-%d') = cd.cd_symd
								where cd.cd_symd <= '".$end_day."' and cd.cd_symd >= '".$start_day."'
								group by cd.cd_symd
								order by cd.cd_symd asc
						";
	$data_obj = sql_list($sql_week);
} else if ($view_type == 'month') {
	//한달치 정보
	$start_day = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
	$end_day = date('Y-m-d', mktime(0, 0, 0, date('m')+1, 0, date('Y')));
	$sql_month = "select cd.cd_symd, cd.cd_sd, ifnull (AVG(bf.weight), 0) as weight_avg, ifnull (AVG(bf.body_fat_rate), 0) as body_fat_rate_avg, ifnull (AVG(bf.BMI), 0) as bmi_avg, ifnull (AVG(bf.body_water_rate), 0) as body_water_rate_avg, cd.cd_symd as bf_regdate
								from rb_calendar_data as cd 
								left join (select * from rb_body_fat where mb_idx = '".$member['mb_idx']."' ) as bf on DATE_FORMAT(bf.bf_regdate, '%Y-%m-%d') = cd.cd_symd
								where cd.cd_symd <= '".$end_day."' and cd.cd_symd >= '".$start_day."'
								group by cd.cd_symd
								order by cd.cd_symd asc
						";
	$data_obj = sql_list($sql_month);
}
$tpl->assign('data_obj', $data_obj);


$tpl->assign('sv_code', $sv_code);

//최초로그인시 
$view_popup = 0; //0:팝업OFF 1:팝업ON
if ($is_member) {
	$sql_chk = "select * from rb_point_setting where ps_idx = 1";
	$data_chk = sql_fetch($sql_chk);
	if ($data_chk['ps_status'] == 1) {
		//포인트 지급했는지 체크
		$sql_ph = "select * from rb_point_history where mb_id = '".$member['mb_id']."' and ph_type = '".$data_chk['ps_idx']."' ";
		$data_ph = sql_fetch($sql_ph);
		if ($data_ph['ph_view'] == 1) {
			$view_popup = 1;
			//본것 업데이트
			$sql_upd = "update rb_point_history set ph_view = 2 where ph_idx = '".$data_ph['ph_idx']."' ";
			sql_query($sql_upd);
		}
	}
}
$tpl->assign('view_popup', $view_popup);
$tpl->assign('ps_point', $data_chk['ps_point']);


include "./inc/_head.php";

$tpl->print_('body');
include "./inc/_tail.php";
?>