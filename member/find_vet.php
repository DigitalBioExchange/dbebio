<?php
include "../inc/_common.php";

$t_menu = 8;
$l_menu = 1;

goto_login();

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'member/find_vet.tpl',

	'left'  =>	'inc/mypage_left.tpl',

	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
));

$sido_data = sql_list("select * from rb_sido where 1 order by sd_idx asc");
$tpl->assign('sido_data', $sido_data); 

$search_query = "";
if($_GET['sd_idx']){
	$search_query .= " and mb_sd_idx = '".$_GET['sd_idx']."' ";
}
if($_GET['si_idx']){
	$search_query .= " and mb_si_idx = '".$_GET['si_idx']."' ";
}
if($_GET['stx']){
	$search_query .= " and (mb_com_name like '%".$_GET['stx']."%' or mb_addr1 like '%".$_GET['stx']."%' or mb_addr2 like '%".$_GET['stx']."%') ";
}

if($_GET['stx'] == "" && !$_GET['si_idx']){
	$search_query .= " and 0 ";
}

$sql = "select * from rb_member where mb_level = 5 and mb_status = 1 and mb_vet = 2 $search_query order by mb_idx desc";
$data = sql_list($sql);
$tpl->assign('data', $data);

include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>