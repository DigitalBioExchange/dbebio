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
	'contents'  =>'member/order_list.tpl',
	'left'  =>	'inc/member_left.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
));

$tpl->assign('page_title', '주문목록');

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
$sql_total = "select * from rb_order  where mb_id = '".$member['mb_id']."' and od_status > 0 $search_query";
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

	$sql = "select * from rb_order where mb_id = '".$member['mb_id']."' and od_status > 0 $search_query $order_query $limit_query";
	$data = sql_list($sql);
	foreach ($data as $key => $value) {
		$sql_pd = "select *, (select fi_name from rb_product_file where pd_idx = ct.pd_idx and fi_num = 0) as fi_name 
								from rb_order_cart as ct
								left join rb_product as pd on pd.pd_idx = ct.pd_idx 
								where ct.od_idx = '".$value['od_idx']."' 
						";
		$data_pd = sql_list($sql_pd);
		foreach ($data_pd as $key_2 => $value_2) {
			$data_pd[$key_2]['pd_name'] = $value_2['pd_name_'.$lang_code];
			$ct_amount_arr = explode('.', $value_2['ct_amount']);
			if (isset($ct_amount_arr[1])) {
				if (strlen($ct_amount_arr[1]) == 1) {
					$ct_amount_arr[1] = $ct_amount_arr[1].'0';
				}
				$ct_amount_text = number_format($ct_amount_arr[0]).'.'.$ct_amount_arr[1];
			} else {
				$ct_amount_text = number_format($ct_amount_arr[0]).'.00';
			}
			$data_pd[$key_2]['ct_amount_text'] = $ct_amount_text;

			//후기작성여부 확인
			$sql_review = "select * from rb_product_review where od_idx = '".$value['od_idx']."' and pd_idx = '".$value_2['pd_idx']."' and mb_id = '".$member['mb_id']."' ";
			$data_review = sql_fetch($sql_review);
			if ($data_review['pr_idx']) {
				$data_pd[$key_2]['ct_review'] = 1;
			} else {
				$data_pd[$key_2]['ct_review'] = 0;
			}

		}
		$data[$key]['product'] = $data_pd;
	}

	$tpl->assign('data', $data); 
}

$order_payment = $_cfg['order']['payment'];
$tpl->assign('order_payment', $order_payment);

$tpl->print_('body');
include "../inc/_tail.php";
?>