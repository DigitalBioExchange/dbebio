<?php
$page_name = "index";
$page_option = "header-white";
include "../inc/_common.php";

if ($user_agent != "app") {
	$gnb = "4";
	goto_login();
}

$t_menu = 4;
$l_menu = 1;

$tpl=new Template;
$tpl->define(array(
	'contents'  =>'company/market_review_load.tpl',
	'left'  => 'inc/member_left.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
));



// GET 값 구해서 각각 필요에 따라 파라미터 만들기
if($_GET['page']){
	$page = $_GET['page'];
}else{
	$page = 1;
}
$querys[] = "page=".$page;

$order_query = "order by pr_idx desc";

$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";
$query_page = (is_array($querys_page) && count($querys_page) > 0) ? implode("&", $querys_page) : "";


// 전체 데이터수 구하기
$sql_total = "select * from rb_product_review where pd_idx = '".$pd_idx."' $search_query";
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

	$sql = "select * from rb_product_review where pd_idx = '".$pd_idx."' $search_query $order_query $limit_query";
	$data = sql_list($sql);

	foreach ($data as $key => $value) {
		$sql_file = "select * from rb_product_review_file where pr_idx = '".$value['pr_idx']."' order by fi_num asc";
		$data_file = sql_list($sql_file);

		foreach ($data_file as $key_2 => $value_2) {
			$info = getimagesize($_SERVER['DOCUMENT_ROOT'].$_cfg['data_dir']."/files/".$value_2['fi_name']);
			$data_file[$key_2]['img_w'] = $info[0];
			$data_file[$key_2]['img_h'] = $info[1];
		}


		$data[$key]['pr_files'] = $data_file;
	}


	$tpl->assign('data', $data); 
}

include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>