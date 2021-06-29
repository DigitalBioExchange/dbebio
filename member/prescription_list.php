<?php
include "../inc/_common.php";
$page_option = "admin";

$t_menu = 8;
$l_menu = 1;

goto_login();

if(!$is_seller){
	alert("권한이 없습니다.", "/index.php");
}

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'member/prescription_list.tpl',

	'left'  =>	'inc/mypage_left2.tpl',

	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',

	'my_top'  => 'inc/my_top2.tpl',
));

//처방식 인증 요청 건수
$total_prescription = sql_total("select * from rb_prescription where vet_id = '".$member['mb_id']."' and pr_status in (1, 2)");
$tpl->assign('total_prescription', $total_prescription);

$total_prescription1 = sql_total("select * from rb_prescription where vet_id = '".$member['mb_id']."' and pr_status = 1");
$tpl->assign('total_prescription1', $total_prescription1);

$total_prescription2 = sql_total("select * from rb_prescription where vet_id = '".$member['mb_id']."' and pr_status = 2");
$tpl->assign('total_prescription2', $total_prescription2);

//누적주문
$total_cart = sql_total("select * from rb_order_cart where vet_id = '".$member['mb_id']."' and ct_status < 10");
$tpl->assign('total_cart', $total_cart);

$total_commission = sql_fetch("select sum(ct_commission) as sm from rb_order_cart where vet_id = '".$member['mb_id']."' and ct_status < 10 and ct_commission_date = '0000-00-00 00:00:00'");
$tpl->assign('total_commission', $total_commission['sm']);

//최신자료
$newest = sql_fetch("select * from rb_pds where 1 order by wi_idx desc");
$tpl->assign('newest', $newest);


$querys = array();
$search_query = "";

if($_GET['pr_status'] != ""){
	$search_query .= " and p.pr_status = '".$_GET['pr_status']."' ";
}
$querys[] = "pr_status=".$_GET['pr_status'];

if($_GET['s_period'] != ""){
	$search_query .= " and SUBSTRING(p.pr_regdate, 1, 10) >= '".date("Y-m-d", strtotime("-".$_GET['s_period']." months", time()))."' ";
}
$querys[] = "s_period=".$_GET['s_period'];

$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

$prescription = sql_list("select p.*, m.mb_name, pet.pe_name, pet.pe_type1, pet.pe_birth, q.pq_idx, q.pq_contents, q.pq_answer, q.pq_img from rb_prescription as p left join rb_pet as pet on p.pe_idx = pet.pe_idx left join rb_member as m on p.mb_id = m.mb_id left join rb_prescription_question as q on p.pr_idx = q.pr_idx where p.vet_id = '".$member['mb_id']."' $search_query order by p.pr_idx desc");
$tpl->assign('prescription', $prescription);

//p_arr($prescription);

include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>