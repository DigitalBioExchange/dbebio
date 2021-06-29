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
	'contents'  =>'company/market_view.tpl',
	'left'  => 'inc/member_left.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
	'photo_swipe'  =>'inc/photo_swipe.tpl',
));

$search_query = "";

$querys = array();
$querys[] = "page=".$page;
$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

$data = sql_fetch("select * from rb_product as b where b.pd_idx = '$pd_idx' $search_query");

if(!$data['pd_idx']) alert($_lang['shop']['text_0694']);

//코인시세 표기
$sql = "select * from rb_coin_price where 1 order by cp_idx desc limit 0, 1";
$data_coin = sql_fetch($sql);
$_price = explode('.', $data_coin['cp_price']);
$data_coin['cp_price_text'] = number_format($_price[0]).".".$_price[1];
$tpl->assign('data_coin', $data_coin); 


//옵션만들기
$data['option1'] = make_product_option_value($data['pd_option1']);
$data['option2'] = make_product_option_value($data['pd_option2']);
//언어표기 만들기
$data['pd_name'] = $data['pd_name_'.$lang_code];
$data['pd_exp'] = $data['pd_exp_'.$lang_code];
$data['pd_contents'] = $data['pd_contents_'.$lang_code];
$data['pd_delivery_contents'] = $data['pd_delivery_contents_'.$lang_code];

if (!$data['option1'] && !$data['option2']) {
	$pd_price_text = $data_coin['cp_price'] * $data['pd_price'];
	$pd_price_arr = explode('.', $pd_price_text);
	if (isset($pd_price_arr[1])) {
		$pd_price_text = number_format($pd_price_arr[0]).'.'.$pd_price_arr[1];
	} else {
		$pd_price_text = number_format($pd_price_arr[0]).'.00';
	}
	$data['pd_price_text'] = $pd_price_text;
}

$tpl->assign('data', $data);

$pd_file_data = sql_fetch("select * from rb_product_file where pd_idx = '$pd_idx' order by fi_num asc");
$tpl->assign('pd_file_data', $pd_file_data);

$tpl->assign('photo_swipe_enable', 1);

include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>