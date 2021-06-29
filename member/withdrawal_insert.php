<?php
$page_name = "bgwhite header-white";
$page_option = "bgwhite header-white";

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
	'contents'  =>'member/withdrawal_insert.tpl',

	'left'  =>	'inc/member_left.tpl',

	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',

	'my_top'  => 'inc/my_top.tpl',
));

$tpl->assign('page_title', '출금신청');
$tpl->assign('mode', 'insert');


//코인시세 표기
$sql = "select * from rb_coin_price where 1 order by cp_idx desc limit 0, 1";
$data_coin = sql_fetch($sql);
$_price = explode('.', $data_coin['cp_price']);
$data_coin['cp_price_text'] = number_format($_price[0]).".".$_price[1];
$tpl->assign('data_coin', $data_coin['cp_price']); 




$tpl->print_('body');
include "../inc/_tail.php";
?>