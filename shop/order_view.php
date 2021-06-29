<?php
$menu_num = 0;
include "../inc/_common.php";
include "../inc/_head.php";

goto_login2();


$t_menu = 8;
$l_menu = 6;


$tpl=new Template;
$tpl->define(array(
    'contents'  =>'shop/order_view.tpl',

	'left'  =>	'inc/mypage_left.tpl',

	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
));

$tpl->assign('page_title', '결제목록');

$querys = array();
$querys[] = "page=".$page;
$order_query = "order by od_idx desc";

$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

$search_query = ($is_member) ? "and mb_id = '".$member[mb_id]."'" : "and od_name = '".$_SESSION[ss_od_name]."' and od_tel = '".$_SESSION[ss_od_tel]."' and od_pass = '".$_SESSION[ss_od_pass]."'";

$sql = "select * from rb_order where od_idx = '$od_idx' $search_query ";
$data = sql_fetch($sql);
if(!$data[od_idx]) alert("없는 주문입니다.");
$tpl->assign('data', $data); 

$cart = sql_list("select * from rb_order_cart as c left join rb_product as p on c.pd_idx = p.pd_idx where c.od_idx = '$od_idx' order by c.ct_idx asc");

for($i=0;$i<count($cart);$i++){
	$_pd_idx = $cart[$i][pd_idx];
	if($_pd_idx){
		$_pd_file_data = sql_fetch("select * from rb_product_file where pd_idx = '$_pd_idx' and fi_num = 0");
		$cart[$i][fi_name] = $_pd_file_data[fi_name];
		$cart[$i][fi_name_org] = $_pd_file_data[fi_name_org];
		$cart[$i][fi_idx] = $_pd_file_data[fi_idx];

	}

	$ct_idx = $cart[$i][ct_idx];
	$ct_status = $cart[$i][ct_status];
	$can_review = 0;

	//리뷰가능여부
	$chk = sql_fetch("select * from rb_product_review where mb_id = '$member[mb_id]' and ct_idx = '$ct_idx'");
	if(!$chk[pr_idx] && $is_member && in_array($ct_status, array(4, 5))){
		$can_review = 1;
	}
	$cart[$i][can_review] = $can_review;
}
$tpl->assign('cart', $cart); 


//배송정보
$delivery = sql_list("select * from rb_delivery_cart as dc left join rb_delivery as d on dc.de_idx = d.de_idx left join rb_order_cart as c on dc.ct_idx = c.ct_idx left join rb_product as p on c.pd_idx = p.pd_idx where dc.od_idx = '$od_idx' order by dc.dt_idx asc");
for($i=0;$i<count($delivery);$i++){
	$_pd_idx = $delivery[$i][pd_idx];
	if($_pd_idx){
		$_pd_file_data = sql_fetch("select * from rb_product_file where pd_idx = '$_pd_idx' and fi_num = 0");
		$delivery[$i][fi_name] = $_pd_file_data[fi_name];
		$delivery[$i][fi_name_org] = $_pd_file_data[fi_name_org];
		$delivery[$i][fi_idx] = $_pd_file_data[fi_idx];
	}
}
$tpl->assign('delivery', $delivery); 

$tpl->print_('body');
include "../inc/_tail.php";
?>