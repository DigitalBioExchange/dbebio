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
	'contents'  =>'member/order_view.tpl',
	'left'  =>	'inc/member_left.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
));

$tpl->assign('page_title', '주문목록');
$tpl->assign('mode', 'insert');

$sql = "select * from rb_order where od_idx = '".$od_idx."' and mb_idx = '".$member['mb_idx']."' and od_status > 0";
$data = sql_fetch($sql);
if (!$data['od_idx']) {
	alert("잘못된 정보입니다.");
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

	//후기작성여부 확인
	$sql_review = "select * from rb_product_review where od_idx = '".$od_idx."' and pd_idx = '".$value_2['pd_idx']."' and mb_id = '".$member['mb_id']."' ";
	$data_review = sql_fetch($sql_review);
	if ($data_review['pr_idx']) {
		$data_pd[$key_2]['ct_review'] = 1;
	} else {
		$data_pd[$key_2]['ct_review'] = 0;
	}

}

$data['product'] = $data_pd;

//배송정보
$deli_list = sql_list("select * from rb_delivery where od_idx = '".$od_idx."' order by de_idx asc");
$data['deli_list'] = $deli_list;


//금액표기 다시 정리--------------------------------------------------------
$total_amount_arr = explode('.', $data['total_amount']);
if (isset($total_amount_arr[1])) {
	if (strlen($total_amount_arr[1]) == 1) {
		$total_amount_arr[1] = $total_amount_arr[1].'0';
	}
	$total_amount_text = number_format($total_amount_arr[0]).'.'.$total_amount_arr[1];
} else {
	$total_amount_text = number_format($total_amount_arr[0]).'.00';
}
$data['total_amount_text'] = $total_amount_text;

$total_delivery_amount_arr = explode('.', $data['total_delivery_amount']);
if (isset($total_delivery_amount_arr[1])) {
	if (strlen($total_delivery_amount_arr[1]) == 1) {
		$total_delivery_amount_arr[1] = $total_delivery_amount_arr[1].'0';
	}
	$total_delivery_amount_text = number_format($total_delivery_amount_arr[0]).'.'.$total_delivery_amount_arr[1];
} else {
	$total_delivery_amount_text = number_format($total_delivery_amount_arr[0]).'.00';
}
$data['total_delivery_amount_text'] = $total_delivery_amount_text;

$total_pay = $data['total_amount'] + $data['total_delivery_amount'];
$total_pay_arr = explode('.', $total_pay);
if (isset($total_pay_arr[1])) {
	if (strlen($total_pay_arr[1]) == 1) {
		$total_pay_arr[1] = $total_pay_arr[1].'0';
	}
	$total_pay_text = number_format($total_pay_arr[0]).'.'.$total_pay_arr[1];
} else {
	$total_pay_text = number_format($total_pay_arr[0]).'.00';
}
$data['total_pay_text'] = $total_pay_text;

$total_pay_amount_arr = explode('.', $data['total_pay_amount']);
if (isset($total_pay_amount_arr[1])) {
	if (strlen($total_pay_amount_arr[1]) == 1) {
		$total_pay_amount_arr[1] = $total_pay_amount_arr[1].'0000';
	}
	$total_pay_amount_text = number_format($total_pay_amount_arr[0]).'.'.$total_pay_amount_arr[1];
} else {
	$total_pay_amount_text = number_format($total_pay_amount_arr[0]).'.0000';
}
$data['total_pay_amount_text'] = $total_pay_amount_text;
//금액표기 다시 정리---------------------------------

$tpl->assign('data', $data);

//국가번호 리스트
$sql_tel = "select * from rb_country_tel_light";
$data_tel = sql_list($sql_tel);
$tpl->assign('data_tel', $data_tel);

$tpl->print_('body');
include "../inc/_tail.php";
?>