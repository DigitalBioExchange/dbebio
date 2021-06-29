<?php
$page_option = "bgwhite header-white";
include "../inc/_common.php";

include "../_inc/_board_config.php";

if(!in_array($bc_code, $_cfg['board']['bc_code'])){
	alert($_lang['board']['text_0658']);
}

$t_menu = $board_config['t_menu'];
$l_menu = $board_config['l_menu'];

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'board/'.$board_config['skin'].'_insert.tpl',
	'left'  => 'inc/'.$board_config['left_skin'].'.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
));
$tpl->assign('bc_code', $bc_code);
$tpl->assign('mode', 'update');
$tpl->assign('board_config', $board_config); 
$search_query = "";


$querys = array();
$querys[] = "page=".$page;
$querys[] = "sca=".$_GET['sca'];
$querys[] = "stx=".$_GET['stx'];
$querys[] = "bc_code=".$bc_code;
if(is_array($board_config['category'])){
	if($_GET['bd_category']){
		$querys[] = "bd_category=".$_GET['bd_category'];
	}
}
$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

if($board_config['is_mine']){
	$search_query .= "  and b.mb_id = '".$member['mb_id']."' ";
}


$data = sql_fetch("select * from rb_board as b left join rb_member as m on b.mb_id = m.mb_id where b.bc_code = '$bc_code' and b.bd_idx = '$bd_idx' $search_query");

if(!$data['bd_idx']) alert($_lang['board']['text_0658']);

if(is_secret_article($board_config, $data['bd_idx'], $data['bd_is_secret'], $data['mb_id'])){
	if($data['bd_idx'] != $_SESSION['board_view'] && !$is_admin){
		alert("비밀글입니다.");
	}
}

if($data['mb_id'] != $member['mb_id'] && !$is_super){
	alert($_lang['board']['text_0661']);
}

$tpl->assign('data', $data);

if($board_config['is_file'] > 0){
	$bd_file_data = sql_list("select * from rb_board_file where bd_idx = '$bd_idx' order by fi_num asc");
	// $start = count($bd_file_data);
	// for($i=$start;$i<$board_config['is_file'];$i++){
	// 	$temp = array();
	// 	$temp['fi_idx'] = "";
	// 	$temp['fi_num'] = "";
	// 	$temp['fi_name'] = "";
	// 	$temp['fi_name_org'] = "";
	// 	$bd_file_data[] = $temp;
	// }
}
$tpl->assign('bd_file_data', $bd_file_data);

include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>