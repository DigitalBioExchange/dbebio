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
    'contents'  =>'board/'.$board_config['skin'].'_view.tpl',
	'left'  => 'inc/'.$board_config['left_skin'].'.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
	'photo_swipe'  =>'inc/photo_swipe.tpl',
));
$tpl->assign('bc_code', $bc_code);
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

$_GET['page_comment'] = $page_comment = ($_GET['page_comment']) ? $_GET['page_comment'] : 1;

$querys_page_comment = $querys;

$querys_page_comment[] = "bd_idx=".$_GET['bd_idx'];

$query_page_comment = (is_array($querys_page_comment) && count($querys_page_comment) > 0) ? implode("&", $querys_page_comment) : "";

if($board_config['is_mine']){
	$search_query .= "  and b.mb_id = '".$member['mb_id']."' ";
}


$data = sql_fetch("select *, if(b.mb_id = '' , b.bd_name, m.mb_name) as bd_writer from rb_board as b left join rb_member as m on b.mb_id = m.mb_id where b.bc_code = '$bc_code' and b.bd_idx = '$bd_idx' $search_query");

if(!$data['bd_idx']) alert($_lang['board']['text_0658']);
if($data['bd_depth'] > 0){
	$data_parent = sql_fetch("select *, if(b.mb_id = '' , b.bd_name, m.mb_name) as bd_writer from rb_board as b left join rb_member as m on b.mb_id = m.mb_id where b.bc_code = '$bc_code' and b.bd_idx = '".$data['bd_parent']."' $search_query");
}


if(is_secret_article($board_config, $data['bd_idx'], $data['bd_is_secret'], $data['mb_id'])){
	if($data['bd_idx'] != $_SESSION['board_view'] && !$is_admin){
		alert($_lang['board']['text_0660']);
	}
}

//좋아요여부
$chk_like = sql_fetch("select * from rb_board_like where bd_idx = '".$data['bd_idx']."' and mb_id = '".$member['mb_id']."'");
$is_like = ($chk_like['li_idx']) ? true : false;
$tpl->assign('is_like', $is_like);

if($data['mb_id'] != $member['mb_id']) sql_query("update rb_board set bd_view_cnt = bd_view_cnt + 1 where bd_idx = '$bd_idx' $search_query");

$tpl->assign('data', $data);

$bd_file_data = sql_list("select * from rb_board_file where bd_idx = '$bd_idx' order by fi_num asc");
if ($bd_file_data) {
	foreach ($bd_file_data as $key => $value) {
		$info = getimagesize($_SERVER['DOCUMENT_ROOT'].$_cfg['data_dir']."/files/".$value['fi_name']);
		$bd_file_data[$key]['img_w'] = $info[0];
		$bd_file_data[$key]['img_h'] = $info[1];
	}
}
$tpl->assign('bd_file_data', $bd_file_data);

if($board_config['is_comment'] && $m_level >= $board_config['comment_list_level']){


	$order_query = "order by cm_idx desc";

	// 전체 데이터수 구하기
	$sql_total = "select * from rb_board_comment as a left join rb_member as m on a.mb_id = m.mb_id where a.bd_idx = '".$data['bd_idx']."'";
	$total = sql_total($sql_total);


	$row_comment = ($user_agent == "web") ? 5 : 5;
	//$total = 2367;
	//$page = 46;
	// 페이징 만들기 시작
	$arr = array('total' => $total,
				 'page' => $page_comment,
				 'row' => $board_config['list_row'],
				 'scale' => $board_config['list_scale'],
				 'center' => $_cfg['admin_paging_center'],
				 'link' => $query_page_comment,
				 'page_name' => "comment"
			);

	try {$paging = C::paging($arr); }
	catch (Exception $e) {
		print 'LINE: '.$e->getLine().' '
					  .C::get_errmsg($e->getmessage());
		exit;
	}
	$tpl->assign($paging);
	$tpl->assign('paging_data_comment', $paging);

	// 페이징 만들기 끝

	if($total){
		$limit_query = " limit ".$paging['query_comment']->limit." offset ".$paging['query_comment']->offset;

		$sql = "select * from rb_board_comment as a left join rb_member as m on a.mb_id = m.mb_id where a.bd_idx = '".$data['bd_idx']."' $order_query $limit_query";
		$data_comment = sql_list($sql);
		for($i=0;$i<count($data_comment);$i++){
			$cm_idx = $data_comment[$i]['cm_idx'];
			$chk_like_c = sql_fetch("select * from rb_board_comment_like where cm_idx = '$cm_idx' and mb_id = '".$member['mb_id']."'");
			$data_comment[$i]['is_like'] = ($chk_like_c['li_idx']) ? true : false;
		}
		$tpl->assign('data_comment', $data_comment); 
	}
}

if($_cfg['function_list']['social_share']){
	$share_data['share_title'] = $data['bd_title'];
	$share_data['share_description'] = mb_substr(strip_tags($data['bd_contents']), 0, 100, mb_internal_encoding());
	if($bd_file_data[0]['fi_name']){
		$share_data['share_image'] = "http://".$_SERVER['SERVER_NAME'].$_cfg['data_dir']."/files/".$bd_file_data[0]['fi_name'];
	}
	$tpl->assign('share_data', $share_data);
}

$tpl->assign('photo_swipe_enable', 1);

include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>