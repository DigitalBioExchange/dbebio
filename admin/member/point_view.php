<?php
$menu_code = "100300";
$menu_mode = "v";

exit;

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

$tpl=new Template;
$tpl->define(array(
	'contents'  =>'member/point_view.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'bottom'  =>'inc/bottom.tpl',
	'second_menu' => 'inc/second_menu.tpl',
));

$tpl->assign('page_title', get_txt_from_data($_cfg['menu_data'], $menu_code, "menu_code", "menu_name")."- 보기");
$tpl->assign('mode', "update");

$search_query = "";


$querys = array();
$querys[] = "page=".$page;
$querys[] = "sca=".$_GET['sca'];
$querys[] = "stx=".$_GET['stx'];
$querys[] = "s_start=".$_GET['s_start'];
$querys[] = "s_end=".$_GET['s_end'];

$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";


$data = sql_fetch("select j.*, m.mb_com_name, m.mb_branch_name, m.mb_grade, m.mb_addr1, m.mb_addr2 from rb_point_history as j left join rb_member as m on j.mb_id = m.mb_id where j.ph_idx = '$ph_idx' $search_query");
$tpl->assign('data', $data);
if(!$data['ph_idx']) alert("없는 포인트기록입니다.");


$tpl->print_('body');
include "../inc/_tail.php";
?>