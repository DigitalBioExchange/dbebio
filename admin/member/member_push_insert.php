<?php
$menu_code = "100400";
$menu_mode = "w";

$limit_access = "";
$limit_access_level = "worker";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'member/member_push_insert.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'bottom'  =>'inc/bottom.tpl',
	'second_menu' => 'inc/second_menu.tpl',
));

$tpl->assign('page_title', get_txt_from_data($_cfg['menu_data'], $menu_code, "menu_code", "menu_name"));
$tpl->assign('mode', "insert");

$search_query = "";

$query = $_POST['query'];

$id_str = "";
$comma = "";
for($i=0;$i<custom_count($_POST['mb_id']);$i++){
	$mb_id = $_POST['mb_id'][$i];
	if($mb_id){
		$id_str .= $comma.$mb_id;
		$comma = ",";
	}
}

$tpl->assign('id_str', $id_str);


$tpl->print_('body');
include "../inc/_tail.php";
?> 
