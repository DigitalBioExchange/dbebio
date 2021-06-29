<?php
$menu_code = "100600";
$menu_mode = "w";


include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'member/myinfo_insert.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'bottom'  =>'inc/bottom.tpl',
	'second_menu' => 'inc/second_menu.tpl',
));

$tpl->assign('page_title', get_txt_from_data($_cfg['menu_data'], $menu_code, "menu_code", "menu_name")."- 수정");
$tpl->assign('mode', "update");

$querys = array();

$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";


$data = sql_fetch("select * from rb_member where mb_idx = '".$member['mb_idx']."' ");
$tpl->assign('data', $data);
if(!$data['mb_idx']) alert("없는 회원입니다.");


$tpl->print_('body');
include "../inc/_tail.php";
?> 
