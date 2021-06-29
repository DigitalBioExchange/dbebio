<?php
$page_name = "header-white";
$page_option = "header-white";
include "../inc/_common.php";
include "../inc/_head.php";

if ($user_agent != "app") {
	$gnb = "4";
	goto_login();
}


$t_menu = 4;
$l_menu = 7;


$tpl=new Template;
$tpl->define(array(
	'contents'  =>'member/cancel_list.tpl',
	'left'  =>	'inc/member_left.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
));

$tpl->assign('page_title', '취소신청 문의');

$querys = array();
$querys_page = array();

// GET 값 구해서 각각 필요에 따라 파라미터 만들기
if($_GET['page']){
	$page = $_GET['page'];
}else{
	$page = 1;
}
$querys[] = "page=".$page;

$search_query = "";


$order_query = "order by od_idx desc";

$query = (is_array($querys) && custom_count($querys) > 0) ? implode("&", $querys) : "";
$query_page = (is_array($querys_page) && custom_count($querys_page) > 0) ? implode("&", $querys_page) : "";

// 전체 데이터수 구하기
$sql_total = "select * from rb_board as bd where bd.bc_code = 'cancel' and bd.mb_id = '".$member['mb_id']."' $search_query";
$total = sql_total($sql_total);


//$total = 2367;
//$page = 46;
// 페이징 만들기 시작
$arr = array('total' => $total,
             'page' => $page,
             'row' => 10,
             'scale' => $_cfg['admin_paging_scale'],
             'center' => $_cfg['admin_paging_center'],
			 'link' => $query_page,
			 'page_name' => ""
        );

try {$paging = C::paging($arr); }
catch (Exception $e) {
    print 'LINE: '.$e->getLine().' '
                  .C::get_errmsg($e->getmessage());
    exit;
}
$tpl->assign($paging);
$tpl->assign('paging_data', $paging);

// 페이징 만들기 끝

if($total){
	$limit_query = " limit ".$paging['query']->limit." offset ".$paging['query']->offset;

	$sql = "select *, (select od_num from rb_order where od_idx = bd.od_idx) as od_num from rb_board as bd where bd.bc_code = 'cancel' and bd.mb_id = '".$member['mb_id']."' $search_query $order_query $limit_query";
	$data = sql_list($sql);

	$tpl->assign('data', $data); 
}



$tpl->print_('body');
include "../inc/_tail.php";
?>