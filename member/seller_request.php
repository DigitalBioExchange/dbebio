<?php
include "../inc/_common.php";

$t_menu = 8;
$l_menu = 11;

goto_login();

if($member['mb_level'] != '1'){
	alert("잘못된 접근입니다." , "/index.php");
}

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'member/seller_request.tpl',

	'left'  =>	'inc/mypage_left.tpl',

	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',

	'my_top'  => 'inc/my_top.tpl',

));


$data = sql_fetch("select * from rb_shop where shop_id = '".$member['mb_id']."'");
$tpl->assign('data', $data);
if($data['sh_idx']){
	if($data['sh_status'] == 1){
		$tpl->assign('mode', "update1");
	}else if($data['sh_status'] == 2){
		goto_url("./seller_info.php");
		$tpl->assign('mode', "update2");
	}else if($data['sh_status'] == 3){
		alert("정지된 상태입니다.", "/");
	}else{
		alert("잘못된 접근입니다." , "/index.php");
	}
}else{
	$tpl->assign('mode', "insert");
}

include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>