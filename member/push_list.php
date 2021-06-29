<?php
$page_name = "header-white";
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
	'contents'  =>'member/push_list.tpl',

	'left'  =>	'inc/member_left.tpl',

	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
));


// GET 값 구해서 각각 필요에 따라 파라미터 만들기
if ($_GET['page']){
	$page = $_GET['page'];
} else {
	$page = 1;
}
$querys[] = "page=".$page;

$order_query = " order by al_idx desc";

$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";
$query_page = (is_array($querys_page) && count($querys_page) > 0) ? implode("&", $querys_page) : "";

// 전체 데이터수 구하기
$sql_total = "select * from rb_alarm_list where mb_idx = '".$member['mb_idx']."' $search_query ";
$total = sql_total($sql_total);


//$total = 2367;
//$page = 46;
// 페이징 만들기 시작
$arr = array('total' => $total,
             'page' => $page,
             'row' => 10,
             'scale' => 5,
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

	$sql = "select * from rb_alarm_list where mb_idx = '".$member['mb_idx']."' $search_query $order_query $limit_query";
	$data = sql_list($sql);

	$tpl->assign('data', $data); 
}


//글자 읽음처리
$sql_upd = "update rb_alarm_list set al_view_check = 0 where mb_idx = '".$member['mb_idx']."' ";
sql_query($sql_upd);


include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>