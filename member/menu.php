<?php
$page_name = "index";
$logo = "active";
$alirm = "active";
$set = "active";
$back = "not";

include "../inc/_common.php";

if ($user_agent != "app") {
	$gnb = "4";
	goto_login();
}

$t_menu = 1;
$l_menu = 1;

$tpl=new Template;
$tpl->define(array(
	'contents'  =>'member/menu.tpl',

	'left'  =>	'inc/member_left.tpl',

	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
));

//최초프로필 작성 지급 포인트 체크
$view_popup = 0; //0:팝업OFF 1:팝업ON
if ($is_member) {
	if ($member['mb_height'] != 0 && $member['mb_birth'] != '0000-00-00') {
		$sql_chk = "select * from rb_point_setting where ps_idx = 2";
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
}
$tpl->assign('view_popup', $view_popup);
$tpl->assign('ps_point', $data_chk['ps_point']);


//건강검징정보 월 1회 등록보상
$view_popup_2 = 0; //0:팝업OFF 1:팝업ON
$view_text = 'off';
if ($is_member) {
	
	$sql_chk_2 = "select * from rb_point_setting where ps_idx = 3";
	$data_chk_2 = sql_fetch($sql_chk_2);
	if ($data_chk_2['ps_status'] == 1) {
		//포인트 지급했는지 체크
		$sql_ph_2 = "select * from rb_point_history where mb_id = '".$member['mb_id']."' and ph_type = '".$data_chk_2['ps_idx']."' and date_format(ph_regdate, '%Y-%m') = '".date('Y-m')."' ";
		$data_ph_2 = sql_fetch($sql_ph_2);

		if ($data_ph_2['ph_view'] == 1) {
			$view_popup_2 = 1;	
			//본것 업데이트
			$sql_upd = "update rb_point_history set ph_view = 2 where ph_idx = '".$data_ph_2['ph_idx']."' ";
			sql_query($sql_upd);

			//최초 디바이스 연동보상
			$sql = "select * from rb_point_setting where ps_idx = 4";
			$data_chk = sql_fetch($sql);
			if ($data_chk['ps_status'] == 1) {
				$sql_ph = "select * from rb_point_history where mb_id = '".$member['mb_id']."' and ph_type = '".$data_chk['ps_idx']."' ";
				$data_ph = sql_fetch($sql_ph);
				if (!$data_ph['ph_idx']) {
					$view_text = 'on';
				}
			}


		}
	}
}
$tpl->assign('view_text', $view_text);
$tpl->assign('view_popup_2', $view_popup_2);
$tpl->assign('ps_point_2', $data_chk_2['ps_point']);


include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>