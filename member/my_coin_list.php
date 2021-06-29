<?php
$page_name = "index";
$page_option = "index";
$logo = "active";
$back = "not";

include "../inc/_common.php";

if ($user_agent != "app") {
	$gnb = "3";
	goto_login();
}

$t_menu = 8;
$l_menu = 1;

// goto_login();

$tpl=new Template;
$tpl->define(array(
	'contents'  =>'member/my_coin_list.tpl',

	'left'  =>	'inc/member_left.tpl',

	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',

	'my_top'  => 'inc/my_top.tpl',
));

//코인시셋 바로 가져오기
$one_dollar = lbxc_coin_api();
$tpl->assign('one_lbxc', $one_dollar['one_lbxc']);

include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>