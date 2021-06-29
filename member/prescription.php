<?php
include "../inc/_common.php";

$t_menu = 8;
$l_menu = 1;

goto_login();

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'member/prescription.tpl',

	'left'  =>	'inc/mypage_left.tpl',

	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
));

$tpl->assign('mode', "insert");

$pet_list = sql_list("select pe.*, p.pr_idx  from rb_pet as pe left join rb_prescription as p on pe.pe_idx = p.pe_idx and p.pr_status <= 3 where pe.mb_id = '".$member['mb_id']."' and pe.pe_type1 = 1 order by pe.pe_idx desc");
if(custom_count($pet_list) == 0){
	alert("먼저 반려동물을 등록하세요.", "/member/pet_insert.php");
}

$tpl->assign('pet_list', $pet_list);
//p_arr($pet_list);
if($_GET['pe_idx']){
	$pe_idx = $_GET['pe_idx'];
	$pet_data = sql_fetch("select pe.*, p.pr_idx, p.vet_id  from rb_pet as pe left join rb_prescription as p on pe.pe_idx = p.pe_idx and p.pr_status <= 3 where pe.mb_id = '".$member['mb_id']."' and pe.pe_type1 = 1 and pe.pe_idx = '$pe_idx'");
}
$tpl->assign('pe_idx', $pe_idx);

$pr_exists = 0;
if($pet_data['pr_idx']){
	$sql = "select * from rb_member where mb_level = 5 and mb_status = 1 and mb_vet = 2 and mb_id = '".$pet_data['vet_id']."'";
	$vet_data = sql_fetch($sql);
	$mb_idx = $vet_data['mb_idx'];
	$pr_exists = 1;
}

if($vet_data['mb_idx']){
	unset($_GET['mb_idx']);
}else if($_GET['mb_idx']){
	$mb_idx = $_GET['mb_idx'];
}
$tpl->assign('mb_idx', $mb_idx);

if($mb_idx){

	$sql = "select * from rb_member where mb_level = 5 and mb_status = 1 and mb_vet = 2 and mb_idx = '$mb_idx'";
	$vet_data = sql_fetch($sql);
	$tpl->assign('vet_data', $vet_data);

	$prescription = sql_fetch("select * from rb_prescription where vet_id = '".$vet_data['mb_id']."' and pe_idx = '$pe_idx'");
	$tpl->assign('prescription', $prescription);
	//p_arr($vet_data);
}

$tpl->assign('pr_exists', $pr_exists);

$sido_data = sql_list("select * from rb_sido where 1 order by sd_idx asc");
$tpl->assign('sido_data', $sido_data); 


include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>