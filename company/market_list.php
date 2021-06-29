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
	'contents'  =>'company/market_list.tpl',

	'left'  =>	'inc/member_left.tpl',

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

$order_query = "order by pd_idx desc";

$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";
$query_page = (is_array($querys_page) && count($querys_page) > 0) ? implode("&", $querys_page) : "";


// 전체 데이터수 구하기
$sql_total = "select * from rb_product where pd_use = 1 $search_query";
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

	$sql = "select * from rb_product where pd_use = 1 $search_query $order_query $limit_query";
	$data = sql_list($sql);

	
	foreach ($data as $key => $value) {
		//상품 이미지 파일
		$sql_file = "select * from rb_product_file where pd_idx = '".$value['pd_idx']."' and fi_num = 0";
		$data_file = sql_fetch($sql_file);
		$data[$key]['fi_name'] = $data_file['fi_name'];
		$data[$key]['fi_name_org'] = $data_file['fi_name_org'];

		//언어표기 만들기
		$data[$key]['pd_name'] = $value['pd_name_'.$lang_code];
		$data[$key]['pd_exp'] = $value['pd_exp_'.$lang_code];
		$data[$key]['pd_contents'] = $value['pd_contents_'.$lang_code];
	}


	$tpl->assign('data', $data);
}

//코인시세 표기
$sql = "select * from rb_coin_price where 1 order by cp_idx desc limit 0, 1";
$data_coin = sql_fetch($sql);
$_price = explode('.', $data_coin['cp_price']);
$data_coin['cp_price_text'] = number_format($_price[0]).".".$_price[1];
$tpl->assign('data_coin', $data_coin); 

include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>