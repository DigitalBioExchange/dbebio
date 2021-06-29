<?php
$page_name = "order_review_insert";
$page_option = "order_review_insert";
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
	'contents'  =>'member/order_reivew_insert.tpl',
	'left'  =>	'inc/member_left.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
));

$tpl->assign('page_title', '후기 작성');
$tpl->assign('mode', 'insert');

$sql = "select * from rb_order_cart where od_idx = '".$od_idx."' and ct_idx = '".$ct_idx."' and mb_id = '".$member['mb_id']."' and ct_status = 9";
$data = sql_fetch($sql);
if (!$data['ct_idx']) {
	alert($_lang['tpl_shop']['text_0548']);
}

//후기작성여부 확인
$sql_review = "select * from rb_product_review where od_idx = '".$od_idx."' and pd_idx = '".$data['pd_idx']."' and mb_id = '".$member['mb_id']."' ";
$data_review = sql_fetch($sql_review);
if ($data_review['pr_idx']) {
	alert($_lang['tpl_shop']['text_0567']);
}

$querys = array();
$querys[] = "page=".$page;

$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	
$sql_pd = "select *, (select fi_name from rb_product_file where pd_idx = ct.pd_idx and fi_num = 0) as fi_name 
						from rb_order_cart as ct
						left join rb_product as pd on pd.pd_idx = ct.pd_idx 
						where ct.od_idx = '".$od_idx."' 
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
}

$data['product'] = $data_pd;

//배송정보
$deli_list = sql_list("select * from rb_delivery where od_idx = '".$od_idx."' order by de_idx asc");
$data['deli_list'] = $deli_list;


$tpl->assign('data', $data);



$tpl->print_('body');
include "../inc/_tail.php";
?>