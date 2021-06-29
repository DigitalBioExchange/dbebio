<?php
include "../inc/_common.php";

$t_menu = 8;
$l_menu = 1;

goto_login();

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'member/address.tpl',

	'left'  =>	'inc/mypage_left.tpl',

	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
	'my_top'  => 'inc/my_top.tpl',
));


//주소록
$address = sql_list("select * from rb_address where mb_id = '".$member['mb_id']."' order by ad_default desc, ad_idx desc");
$tpl->assign('address', $address);

include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>