<?php
$page_name = "index";
$page_option = "bgwhite header-white";
include "../inc/_common.php";

if ($user_agent != "app") {
	$gnb = "2";
	goto_login();
}

$t_menu = 1;
$l_menu = 1;


$tpl=new Template;
$tpl->define(array(
	'contents'  =>'company/measurement_result_graph.tpl',

	'left'  =>	'inc/member_left.tpl',

	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
));

if ($_GET['view_type']) {
	$view_type = $_GET['view_type'];
} else {
	$view_type = 'day';
}

if ($view_type == 'day') {
	$sql = "select * from rb_body_fat 
					where date_format(bf_regdate, '%Y-%m-%d') = CURDATE() and mb_idx = '".$member['mb_idx']."'
					group by date_format(bf_regdate, '%Y-%m-%d %H')
				";
	$date_obj = sql_list($sql);
} else if ($view_type == 'week') {
	//일주일치 정보
	$day_of_week = date('w');
	$today = date('Y-m-d');
	$start_day = date('Y-m-d', strtotime($today." -".$day_of_week."days"));
	$end_day = date('Y-m-d', strtotime($start_day."+6 days"));
	$sql_week = "select cd.cd_symd, cd.cd_sd, ifnull (AVG(bf.weight), 0) as weight, ifnull (AVG(bf.body_fat_rate), 0) as body_fat_rate, ifnull (AVG(bf.BMI), 0) as BMI, ifnull (AVG(bf.body_water_rate), 0) as body_water_rate, ifnull (AVG(bf.bone_mass), 0) as bone_mass, ifnull (AVG(bf.BMR), 0) as BMR, cd.cd_symd as bf_regdate
								from rb_calendar_data as cd 
								left join (select * from rb_body_fat where mb_idx = '".$member['mb_idx']."' ) as bf on DATE_FORMAT(bf.bf_regdate, '%Y-%m-%d') = cd.cd_symd
								where cd.cd_symd <= '".$end_day."' and cd.cd_symd >= '".$start_day."'
								group by cd.cd_symd
								order by cd.cd_symd asc
						";
	$date_obj = sql_list($sql_week);
	
} else if ($view_type == 'month') {
	//한달치 정보
	$start_day = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
	$end_day = date('Y-m-d', mktime(0, 0, 0, date('m')+1, 0, date('Y')));
	$sql_month = "select cd.cd_symd, cd.cd_sd, ifnull (AVG(bf.weight), 0) as weight, ifnull (AVG(bf.body_fat_rate), 0) as body_fat_rate, ifnull (AVG(bf.BMI), 0) as BMI, ifnull (AVG(bf.body_water_rate), 0) as body_water_rate, ifnull (AVG(bf.bone_mass), 0) as bone_mass, ifnull (AVG(bf.BMR), 0) as BMR, cd.cd_symd as bf_regdate
								from rb_calendar_data as cd 
								left join (select * from rb_body_fat where mb_idx = '".$member['mb_idx']."' ) as bf on DATE_FORMAT(bf.bf_regdate, '%Y-%m-%d') = cd.cd_symd
								where cd.cd_symd <= '".$end_day."' and cd.cd_symd >= '".$start_day."'
								group by cd.cd_symd
								order by cd.cd_symd asc
						";
	$date_obj = sql_list($sql_month);
} else if ($view_type == 'year') {
	//일년치 정보
	$start_day = date('Y-m-d', mktime(0, 0, 0, 1, 1, date('Y')));
	$end_day = date('Y-m-d', mktime(0, 0, 0, 12, 31, date('Y')));
	$sql_year = "select cd.cd_symd, cd.cd_sd, ifnull (AVG(bf.weight), 0) as weight, ifnull (AVG(bf.body_fat_rate), 0) as body_fat_rate, ifnull (AVG(bf.BMI), 0) as BMI, ifnull (AVG(bf.body_water_rate), 0) as body_water_rate, ifnull (AVG(bf.bone_mass), 0) as bone_mass, ifnull (AVG(bf.BMR), 0) as BMR, cd.cd_symd as bf_regdate
								from rb_calendar_data as cd 
								left join (select * from rb_body_fat where mb_idx = '".$member['mb_idx']."' ) as bf on DATE_FORMAT(bf.bf_regdate, '%Y-%m-%d') = cd.cd_symd
								where cd.cd_symd <= '".$end_day."' and cd.cd_symd >= '".$start_day."'
								group by cd.cd_sm
								order by cd.cd_symd asc
						";
	$date_obj = sql_list($sql_year);
}

$tpl->assign('date_obj', $date_obj);

include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>