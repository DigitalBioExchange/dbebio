<?php
$menu_code = "100100";
$menu_mode = "w";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

$tpl=new Template;
$tpl->define(array(
	'contents'  =>'member/member_insert.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'bottom'  =>'inc/bottom.tpl',
	'second_menu' => 'inc/second_menu.tpl',
	'find_address' => 'admin/inc/find_address.tpl',
));

$tpl->assign('page_title', get_txt_from_data($_cfg['menu_data'], $menu_code, "menu_code", "menu_name")."- 수정");
$tpl->assign('mode', "update");

$search_query = "";


$querys = array();
$querys[] = "page=".$page;
$querys[] = "sca=".$_GET['sca'];
$querys[] = "stx=".$_GET['stx'];
$querys[] = "mb_status=".$_GET['mb_status'];
//$querys[] = "mb_level=".$_GET['mb_level'];
$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";


$data = sql_fetch("select m.* from rb_member as m where m.mb_level < ".$_cfg['seller_level']." and mb_status > 0 and m.mb_idx = '$mb_idx' $search_query");
$tpl->assign('data', $data);
if(!$data['mb_idx']) alert("없는 회원입니다.");

//SNS
$sns_data1 = sql_fetch("select * from rb_sns where mb_id = '".$data['mb_id']."' and ss_from = 'facebook'");
$tpl->assign('sns_data1', $sns_data1);

$sns_data2 = sql_fetch("select * from rb_sns where mb_id = '".$data['mb_id']."' and ss_from = 'kakao'");
$tpl->assign('sns_data2', $sns_data2);

$sns_data3 = sql_fetch("select * from rb_sns where mb_id = '".$data['mb_id']."' and ss_from = 'naver'");
$tpl->assign('sns_data3', $sns_data3);

$tpl->print_('body');
include "../inc/_tail.php";
?> 
