<?php
$page_name = "index";
$page_option = "index";
// $logo = "active";
// $back = "not";

include "../inc/_common.php";
include "../inc/_head.php";

if ($user_agent != "app") {
	$gnb = "4";
	goto_login();
}


$t_menu = 8;
$l_menu = 7;


$tpl=new Template;
$tpl->define(array(
	'contents'  =>'member/withdrawal_list.tpl',

	'left'  =>	'inc/member_left.tpl',

	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',

	'my_top'  => 'inc/my_top.tpl',
));

$tpl->assign('page_title', '출금신청목록');


// GET 값 구해서 각각 필요에 따라 파라미터 만들기
if ($_GET['page']){
	$page = $_GET['page'];
} else {
	$page = 1;
}
$querys[] = "page=".$page;

$order_query = " order by wl_idx desc";

$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";
$query_page = (is_array($querys_page) && count($querys_page) > 0) ? implode("&", $querys_page) : "";

// 전체 데이터수 구하기
$sql_total = "select * from rb_withdrawal_list where mb_id = '".$member['mb_id']."' $search_query group by date_format(wl_regdate, '%Y-%m-%d') $order_query";
$total = sql_total2($sql_total);


//$total = 2367;
//$page = 46;
// 페이징 만들기 시작
$arr = array('total' => $total,
             'page' => $page,
             'row' => 5,
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

	$sql = "select * from rb_withdrawal_list where mb_id = '".$member['mb_id']."' $search_query group by date_format(wl_regdate, '%Y-%m-%d') $order_query $limit_query";
	$data = sql_list($sql);

	foreach ($data as $key => $value) {
		$sql_sub = "select * from rb_withdrawal_list where mb_id = '".$member['mb_id']."' and date_format(wl_regdate, '%Y-%m-%d') = '".substr($value['wl_regdate'], 0, 10)."' $order_query";

		$data_sub = sql_list($sql_sub);
		$data[$key]['data_sub'] = $data_sub;
	}

	$tpl->assign('data', $data); 
}



$tpl->print_('body');
include "../inc/_tail.php";
?>