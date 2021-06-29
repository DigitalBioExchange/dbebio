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
	'find_address' => 'admin/inc/find_address.tpl',
));

$tpl->assign('page_title', get_txt_from_data($_cfg['menu_data'], $menu_code, "menu_code", "menu_name")."- 수정");
$tpl->assign('mode', "reply");

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

if(is_array($board_config['category'])){
	if($_GET['bd_category']){
		$querys[] = "bd_category=".$_GET['bd_category'];
	}
}

$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

$data_parent = sql_fetch("select * from rb_board as b left join rb_member as m on b.mb_id = m.mb_id where b.bc_code = '$bc_code' and b.bd_idx = '$bd_idx' $search_query");

if(!$data_parent['bd_idx']) alert("원글이 없는 게시글입니다.");
if($data_parent['bd_depth'] > 0) alert("답글엔 답글을 달수 없습니다.");


$tpl->assign('data_parent', $data_parent);

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
