<?php
include "../inc/_common.php";

$t_menu = 8;
$l_menu = 1;

goto_login();

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'member/mypage.tpl',

	'left'  =>	'inc/mypage_left.tpl',

	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',

	'my_top'  => 'inc/my_top.tpl',
));

$pet_list = sql_list("select * from rb_pet where mb_id = '".$member['mb_id']."' order by pe_idx desc");
for($i=0;$i<custom_count($pet_list);$i++){
	$pet_list[$i]['is_routin'] = sql_total("select * from rb_order_cart as c left join rb_order as o on c.od_idx = o.od_idx where c.pe_idx = '".$pet_list[$i]['pe_idx']."' and o.od_type = 1 and o.od_status = 9");
}
$tpl->assign('pet_list', $pet_list); 


//최근 1개월간 주문/배송
for($i=1;$i<=8;$i++){
	${"od_cnt".$i} = sql_total("select * from rb_order where mb_id = '".$member['mb_id']."' and od_status = '$i' and SUBSTRING(od_regdate, 1, 10) >= '".date("Y-m-d", strtotime("-1 months", time()))."'  and SUBSTRING(od_regdate, 1, 10) <= '".date("Y-m-d")."'");
	$tpl->assign('od_cnt'.$i, ${"od_cnt".$i}); 
}

//상담내역
//1일반문의
$question1 = sql_total("select * from rb_question where mb_id = '".$member['mb_id']."' and qu_type = 1 and qu_regdate >= '".date('Y-m-d H:i:s', strtotime("-1 months", time()))."'");
$tpl->assign('question1', $question1); 
//2.상품문의
$question2 = sql_total("select * from rb_product_qna where mb_id = '".$member['mb_id']."' and pq_regdate >= '".date('Y-m-d H:i:s', strtotime("-1 months", time()))."' ");
$tpl->assign('question2', $question2); 
//3.식단문의
$question3 = sql_total("select * from rb_question where mb_id = '".$member['mb_id']."' and qu_type = 2 and qu_regdate >= '".date('Y-m-d H:i:s', strtotime("-1 months", time()))."'");
$tpl->assign('question3', $question3); 

//처방식 구매인증
$prescription = sql_list("select * from rb_prescription where mb_id = '".$member['mb_id']."' order by pr_idx desc");
for($i=0;$i<custom_count($prescription);$i++){
	$sql = "select * from rb_member where mb_level = 5 and mb_status = 1 and mb_vet = 2 and mb_id = '".$prescription[$i]['vet_id']."'";
	$prescription[$i]['vet_data'] = sql_fetch($sql);
	$prescription[$i]['prescription_question'] = sql_fetch("select * from rb_prescription_question where pr_idx = '".$prescription[$i]['pr_idx']."'");
}
$tpl->assign('prescription', $prescription);

//나의후기 (안쓴상품)
$hugi = sql_list("select p.* from rb_order_cart as c left join rb_product as p on c.pd_idx = p.pd_idx where c.mb_id = '".$member['mb_id']."' and c.pr_idx = 0 and c.ct_status = 5 order by c.ct_idx desc");
$tpl->assign('hugi', $hugi);

//후기 추천수
$hugi_like_cnt = sql_fetch("select sum(pr_like_cnt) as sm from rb_product_review where mb_id = '".$member['mb_id']."'");
$tpl->assign('hugi_like_cnt', $hugi_like_cnt['sm']);

include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>