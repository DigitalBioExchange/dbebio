<?php
$menu_code = "400501";
$menu_mode = "v";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'product/exhibition_issue.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'bottom'  =>'inc/bottom.tpl',
	'second_menu' => 'inc/second_menu.tpl',
));

$tpl->assign('page_title', get_txt_from_data($_cfg['menu_data'], $menu_code, "menu_code", "menu_name")."- 발급");
$tpl->assign('mode', "issue");

$querys = array();
$querys[] = "page=".$page;
$querys[] = "sca=".$_GET['sca'];
$querys[] = "stx=".$_GET['stx'];

$data = sql_fetch("select * from rb_exhibition as c where c.ex_idx = '$ex_idx' ");
if(!$data['ex_idx']) alert("없는 기획전입니다.");
$tpl->assign('data', $data);

//등록된 상품목록
$product = sql_list("select * from rb_exhibition_product as e left join rb_product as p on e.pd_idx = p.pd_idx where ex_idx = '$ex_idx' order by e.ep_sort asc");
$tpl->assign('product', $product);

$tpl->print_('body');
include "../inc/_tail.php";
?> 
