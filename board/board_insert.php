<?php
$page_option = "bgwhite header-white";
include "../inc/_common.php";

include "../_inc/_board_config.php";

if(!in_array($bc_code, $_cfg['board']['bc_code'])){
	alert($_lang['board']['text_0658']);
}
if ($user_agent != "app") {
	$gnb = "4";
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
$tpl->assign('mode', 'insert');
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

if($board_config['is_file'] > 0){
	$bd_file_data = array();
	for($i=0;$i<$board_config['is_file'];$i++){
		// $temp = array();
		// $temp['fi_idx'] = "";
		// $temp['fi_num'] = "";
		// $temp['fi_name'] = "";
		// $temp['fi_name_org'] = "";
		// $bd_file_data[] = $temp;
	}
}
$tpl->assign('bd_file_data', $bd_file_data);

include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>