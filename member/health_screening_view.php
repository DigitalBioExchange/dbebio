<?php
$page_name = "index";
$page_option = "header-white";

include "../inc/_common.php";

if ($user_agent != "app") {
	$gnb = "4";
	goto_login();
}

$t_menu = 1;
$l_menu = 1;

$tpl=new Template;
$tpl->define(array(
	'contents'  =>'member/health_screening_view.tpl',

	'left'  =>	'inc/member_left.tpl',

	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
	'photo_swipe'  =>'inc/photo_swipe.tpl',
));


$querys = array();
$querys[] = "page=".$page;

$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

$data = sql_fetch("select * from rb_health_screening where hs_idx = '$hs_idx' and mb_idx = '".$member['mb_idx']."' $search_query");

if(!$data['hs_idx']) alert($_lang['inc']['text_0746']);

$tpl->assign('data', $data);

$sql_file = "select * from rb_health_file where hs_idx = '".$data['hs_idx']."' order by fi_num asc ";
$data_file = sql_list($sql_file);
if ($data_file) {
	foreach ($data_file as $key => $value) {
		$info = getimagesize($_SERVER['DOCUMENT_ROOT'].$_cfg['data_dir']."/files/".$value['fi_name']);
		$data_file[$key]['img_w'] = $info[0];
		$data_file[$key]['img_h'] = $info[1];
	}
}
$tpl->assign('data_file', $data_file);

$tpl->assign('photo_swipe_enable', 1);

include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>