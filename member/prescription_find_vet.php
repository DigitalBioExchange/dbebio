<?php
include "../inc/_common.php";

$t_menu = 8;
$l_menu = 1;

goto_login();

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'member/prescription_find_vet.tpl',

	'left'  =>	'inc/mypage_left.tpl',

	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
));

$tpl->assign('mode', "insert");

$pet_list = sql_list("select * from rb_pet as pe left join rb_prescription as p on pe.pe_idx = p.pe_idx and p.pr_status = 3 and p.pr_edate >= '".date('Y-m-d')."' where pe.mb_id = '".$member['mb_id']."' and pe.pe_type1 = 1 order by pe.pe_idx desc");
if(custom_count($pet_list) == 0){
	alert("먼저 반려동물을 등록하세요.", "/member/pet_insert.php");
}

$tpl->assign('pet_list', $pet_list);

if($_GET['pe_idx']){
	$pe_idx = $_GET['pe_idx'];
}
$tpl->assign('pe_idx', $pe_idx);

if($_GET['mb_idx']){
	$mb_idx = $_GET['mb_idx'];
}
$tpl->assign('mb_idx', $mb_idx);

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