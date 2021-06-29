<?php
$menu_mode = "v";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'board/board_view.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'bottom'  =>'inc/bottom.tpl',
	'second_menu' => 'inc/second_menu.tpl',
));

$tpl->assign('page_title', get_txt_from_data($_cfg['menu_data'], $menu_code, "menu_code", "menu_name")."- 보기");
$tpl->assign('mode', "update");

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


$data = sql_fetch("select *, if(b.mb_id = '' , b.bd_name, m.mb_name) as bd_writer from rb_board as b left join rb_member as m on b.mb_id = m.mb_id where b.bc_code = '$bc_code' and b.bd_idx = '$bd_idx' $search_query");

if(!$data['bd_idx']) alert("없는 게시글입니다.");
if($data['bd_depth'] > 0){
	$data_parent = sql_fetch("select * from rb_board as b left join rb_member as m on b.mb_id = m.mb_id where b.bc_code = '$bc_code' and b.bd_idx = '".$data['bd_parent']."' $search_query");
}
$tpl->assign('data_parent', $data_parent);

/*
//조회수 체크
if($data[mb_id] != $member[mb_id]){
	$chk = sql_fetch("select * from rb_board_history where bd_idx = '$bd_idx' and (mb_id = '$member[mb_id]' or bh_ip = '$_SERVER[REMOTE_ADDR]')");
	if(!$chk[bh_idx]){
		sql_query("insert into rb_board_history set bd_idx = '$bd_idx', bc_code = '$data[bc_code]', mb_id = '$member[mb_id]', bh_ip = '$_SERVER[REMOTE_ADDR]', bh_regdate = now()");
		$data[bd_view_cnt] = $data[bd_view_cnt] + 1;
		sql_query("update rb_board set bd_view_cnt = bd_view_cnt + 1 where bd_idx = '$bd_idx'");

	}
}
*/

$tpl->assign('data', $data);

$bd_file_data = sql_list("select * from rb_board_file where bd_idx = '$bd_idx' order by fi_num asc");
$tpl->assign('bd_file_data', $bd_file_data);

if($board_config['is_comment']){
	//댓글목록
	$comment_data = sql_list("select * from rb_board_comment where bd_idx = '$bd_idx' order by cm_idx desc");
	$tpl->assign('comment_data', $comment_data);
}


$tpl->print_('body');
include "../inc/_tail.php";
?>