<?php
include "../inc/_common.php";

$t_menu = 8;
$l_menu = 1;

goto_login();

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'member/pet_insert.tpl',

	'left'  =>	'inc/mypage_left.tpl',

	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
));

$tpl->assign('mode', "update");

$data = sql_fetch("select * from rb_pet where mb_id = '".$member['mb_id']."' and pe_idx = '$pe_idx'");
if(!$data['pe_idx']){
	alert("없는 반려동물입니다.");
}
$tpl->assign('data', $data);


include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>