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
	'contents'  =>'member/order_cancel_insert.tpl',
	'left'  =>	'inc/member_left.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
));

$tpl->assign('page_title', '취소신청');
$tpl->assign('mode', 'insert');

$sql = "select * from rb_order where od_idx = '".$od_idx."' and mb_idx = '".$member['mb_idx']."' and od_status in (1, 2)";
$data = sql_fetch($sql);
if (!$data['od_idx']) {
	alert($_lang['tpl_shop']['text_0548']);
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

$tpl->assign('data', $data);


$tpl->print_('body');
include "../inc/_tail.php";
?>