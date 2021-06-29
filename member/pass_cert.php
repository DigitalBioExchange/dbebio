<?php
include "../inc/_common.php";

$t_menu = 9;
$l_menu = 4;

already_logged();

$mb = get_member($mb_id);
$cert = md5($mb['mb_id'].$mb['mb_name'].$mb['mb_verify_word2']);


if($cert == $mb_cert){
	sql_query("update rb_member set mb_pass = mb_new_pass , mb_verify_word2 = '' where mb_id = '$mb_id'");
	$tpl_name = "pass_cert_ok";
}else{
	$tpl_name = "pass_cert_fail";
}

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'member/'.$tpl_name.'.tpl',
	'left'  =>	'inc/member_left.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
));

include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>