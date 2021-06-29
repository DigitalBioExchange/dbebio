<?php
$menu_code = "100500";
$menu_mode = "w";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'member/member_worker_insert.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'bottom'  =>'inc/bottom.tpl',
	'second_menu' => 'inc/second_menu.tpl',
));

$tpl->assign('page_title', get_txt_from_data($_cfg['menu_data'], $menu_code, "menu_code", "menu_name")."- 등록");
$tpl->assign('mode', "insert");

$search_query = "";


$querys = array();
$querys[] = "page=".$page;
$querys[] = "sca=".$_GET['sca'];
$querys[] = "stx=".$_GET['stx'];
$querys[] = "mb_status=".$_GET['mb_status'];

$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";


// 권한 목록
$data['auth_v_arr'] = explode(",", $data["auth_v"]);
$data['auth_w_arr'] = explode(",", $data["auth_w"]);

$temp = array();
$cnt = 0;
for($i=0;$i<count($_cfg['menu_data']);$i++){
	if(strlen($_cfg['menu_data'][$i]['menu_code']) == 6){
		$cnt++;
		$tmp = array();
		$tmp['menu_code'] = $_cfg['menu_data'][$i]['menu_code'];
		$tmp['menu_name'] = $_cfg['menu_data'][$i]['menu_name'];
		$tmp['checked_v'] = (in_array($tmp['menu_code'], $data['auth_v_arr']) || $data['user_level'] == 9) ? "checked" : "";
		$tmp['checked_w'] = (in_array($tmp['menu_code'], $data['auth_w_arr']) || $data['user_level'] == 9) ? "checked" : "";
		$tmp['last'] = (strlen($_cfg['menu_data'][($i+1)]['menu_code']) == 3 || !$_cfg['menu_data'][($i+1)]['menu_code']) ? $cnt : "";
		$temp[$last_i]['cnt'] = $cnt;
		$tmp['top_menu_code'] = substr($_cfg['menu_data'][$i]['menu_code'], 0, 3);
		$temp[] = $tmp;

	}else{
		$tmp = array();
		$tmp['menu_code'] = "";
		$tmp['menu_name'] = $_cfg['menu_data'][$i]['menu_name'];
		$tmp['top_menu_code'] = $_cfg['menu_data'][$i]['menu_code'];
		$tmp['cnt'] = 0;
		$temp[] = $tmp;
		$last_i = $i;
		$cnt = 0;
	}
}
$auth_data = $temp;

$tpl->assign('auth_data', $auth_data);

$tpl->print_('body');
include "../inc/_tail.php";
?> 
