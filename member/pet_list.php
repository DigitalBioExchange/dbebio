<?php
include "../inc/_common.php";

$t_menu = 8;
$l_menu = 1;

goto_login();

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'member/pet_list.tpl',

	'left'  =>	'inc/mypage_left.tpl',

	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',

	'my_top'  => 'inc/my_top.tpl',
));

$tpl->assign('mode', "insert");

$pet_list = sql_list("select * from rb_pet where mb_id = '".$member['mb_id']."' order by pe_idx desc");
$tpl->assign('pet_list', $pet_list);

if($_GET['pe_idx']){
	$pe_idx = $_GET['pe_idx'];
	$data = sql_fetch("select * from rb_pet where mb_id = '".$member['mb_id']."' and pe_idx = '$pe_idx'");
}

if(!$data['pe_idx']){
	$data = $pet_list[0];
}

$pe_idx = $data['pe_idx'];
sql_query("update rb_member set mb_pet = '".$pe_idx."' where mb_id = '".$member['mb_id']."'");
$member['mb_pet'] = $pe_idx;

$tpl->assign('pe_idx', $pe_idx);
$tpl->assign('data', $data);

//정기주문
$routin = sql_list("select * from rb_period_cart as c left join rb_pet as pe on c.pe_idx = pe.pe_idx left join rb_product as p on c.pd_idx = p.pd_idx where c.pe_idx = '".$pe_idx."' and ct_status = 9 order by ct_idx desc");

$tpl->assign('routin', $routin);
//p_arr($routin);

//최근구매
$recent = sql_list("select c.*, p.pd_img, p.pd_name from rb_order_cart as c left join rb_product as p on c.pd_idx = p.pd_idx where c.pe_idx = '".$pe_idx."' and c.ct_status in (2, 3, 4, 5)");
$tpl->assign('recent', $recent);

include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>