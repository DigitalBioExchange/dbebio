<?php
include "../inc/_common.php";

$t_menu = 8;
$l_menu = 1;

goto_login();

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'member/prescription_question_insert.tpl',

	'left'  =>	'inc/mypage_left.tpl',

	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
));

$tpl->assign('mode', "insert");

$prescription = sql_fetch("select * from rb_prescription where mb_id = '".$member['mb_id']."' and pr_idx = '$pr_idx'");
if(!$prescription['pr_idx']){
	alert("처방식 구매 인증요청이 없습니다.");
}
$tpl->assign('prescription', $prescription);

$prescription_question = sql_fetch("select * from rb_prescription_question where pr_idx = '$pr_idx'");
$tpl->assign('prescription_question', $prescription_question);

$sql = "select * from rb_member where mb_level = 5 and mb_status = 1 and mb_vet = 2 and mb_id = '".$prescription['vet_id']."'";
$vet_data = sql_fetch($sql);
$tpl->assign('vet_data', $vet_data);

include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>