<?php
$menu_mode = "w";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'board/board_insert.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'bottom'  =>'inc/bottom.tpl',
	'second_menu' => 'inc/second_menu.tpl',
));

$tpl->assign('page_title', get_txt_from_data($_cfg['menu_data'], $menu_code, "menu_code", "menu_name")."- 등록");
$tpl->assign('mode', "insert");

if(!in_array($bc_code, $_cfg['board']['bc_code'])){
	alert("없는 게시판입니다.");
}

$tpl->assign('bc_code', $bc_code); 
$board_config = $_cfg['board_config'][$bc_code];
$tpl->assign('board_config', $board_config); 

$search_query = "";


$querys = array();
$querys[] = "bc_code=".$bc_code;
$querys[] = "page=".$page;
$querys[] = "sca=".$_GET['sca'];
$querys[] = "stx=".$_GET['stx'];
if($board_config['is_teacher']){
	if($_GET['te_idx']){
		$querys[] = "te_idx=".$_GET['te_idx'];
	}
}

if(is_array($board_config['category']) && count($board_config['category']) > 0){
	if($_GET['bd_category']){
		$querys[] = "bd_category=".$_GET['bd_category'];
	}
}
$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

if($board_config['is_file'] > 0){
	$bd_file_data = array();
	for($i=0;$i<$board_config['is_file'];$i++){
		$temp = array();
		$temp['fi_idx'] = "";
		$temp['fi_num'] = "";
		$temp['fi_name'] = "";
		$temp['fi_name_org'] = "";
		$bd_file_data[] = $temp;
	}
}
$tpl->assign('bd_file_data', $bd_file_data);


$tpl->print_('body');
include "../inc/_tail.php";
?> 
