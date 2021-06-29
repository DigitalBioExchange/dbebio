<?php
include "../inc/_common.php";

include "../_inc/_product_config.php";

$t_menu = 4;
$l_menu = 1;

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'company/product_view.tpl',
	'left'  => 'inc/product_left.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
));
$product_config = $_cfg['product_config'];
$tpl->assign('product_config', $product_config); 
$search_query = "";


$querys = array();
$querys[] = "page=".$page;
$querys[] = "sca=".$_GET[sca];
$querys[] = "stx=".$_GET[stx];
if(is_array($product_config[category])){
	if($_GET[pd_category]){
		$querys[] = "pd_category=".$_GET[pd_category];
	}
}

$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

$_GET[page_comment] = $page_comment = ($_GET[page_comment]) ? $_GET[page_comment] : 1;

$querys_page_comment = $querys;

$querys_page_comment[] = "pd_idx=".$_GET[pd_idx];

$query_page_comment = (is_array($querys_page_comment) && count($querys_page_comment) > 0) ? implode("&", $querys_page_comment) : "";


$data = sql_fetch("select * from rb_product as b where b.pd_idx = '$pd_idx' $search_query");

if(!$data[pd_idx]) alert("없는 상품입니다.");


//좋아요여부
$chk_like = sql_fetch("select * from rb_product_like where pd_idx = '$data[pd_idx]' and mb_id = '$member[mb_id]'");
$is_like = ($chk_like[li_idx]) ? true : false;
$tpl->assign('is_like', $is_like);

if($data[mb_id] != $member[mb_id]) sql_query("update rb_product set pd_view_cnt = pd_view_cnt + 1 where pd_idx = '$pd_idx' $search_query");

$tpl->assign('data', $data);

$pd_file_data = sql_list("select * from rb_product_file where pd_idx = '$pd_idx' order by fi_num asc");
$tpl->assign('pd_file_data', $pd_file_data);

if($product_config[is_comment] && $m_level >= $product_config[comment_list_level]){


	$order_query = "order by cm_idx desc";

	// 전체 데이터수 구하기
	$sql_total = "select * from rb_product_comment as a left join rb_member as m on a.mb_id = m.mb_id where a.pd_idx = '".$data[pd_idx]."'";
	$total = sql_total($sql_total);


	$row_comment = ($user_agent == "web") ? 5 : 5;
	//$total = 2367;
	//$page = 46;
	// 페이징 만들기 시작
	$arr = array('total' => $total,
				 'page' => $page_comment,
				 'row' => $product_config[list_row],
				 'scale' => $product_config[list_scale],
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

		$sql = "select * from rb_product_comment as a left join rb_member as m on a.mb_id = m.mb_id where a.pd_idx = '".$data[pd_idx]."' $order_query $limit_query";
		$data_comment = sql_list($sql);
		for($i=0;$i<count($data_comment);$i++){
			$cm_idx = $data_comment[$i][cm_idx];
			$chk_like_c = sql_fetch("select * from rb_product_comment_like where cm_idx = '$cm_idx' and mb_id = '$member[mb_id]'");
			$data_comment[$i][is_like] = ($chk_like_c[li_idx]) ? true : false;
		}
		$tpl->assign('data_comment', $data_comment); 
	}
}

if($_cfg['function_list']['social_share']){
	$share_data[share_title] = $data[pd_name];
	$share_data[share_description] = mb_substr(strip_tags($data[pd_exp]), 0, 100, mb_internal_encoding());
	if($pd_file_data[0][fi_name]){
		$share_data[share_image] = "http://".$_SERVER[SERVER_NAME].$_cfg[data_dir]."/files/".$pd_file_data[0][fi_name];
	}
	$tpl->assign('share_data', $share_data);
}

include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>