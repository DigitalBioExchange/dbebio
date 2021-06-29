<?php
include "../inc/_common.php";
include "../inc/_head.php";

goto_login();

//p_arr($_POST);exit;

$_POST['sh_si_idx'] = substr($_POST['sh_bcode'], 0, 5);
$_POST['sh_sd_idx'] = substr($_POST['sh_bcode'], 0, 2);


$sql_common = "
	sh_name = '".$_POST['sh_name']."',
	sh_com_name = '".$_POST['sh_com_name']."',
	sh_ceo_name = '".$_POST['sh_ceo_name']."',
	sh_tel = '".$_POST['sh_tel']."',
	sh_fax = '".$_POST['sh_fax']."',
	sh_saupja = '".$_POST['sh_saupja']."',
	sh_cs_time = '".$_POST['sh_cs_time']."',

	sh_zip = '".$_POST['sh_zip']."',
	sh_addr1 = '".$_POST['sh_addr1']."',
	sh_addr2 = '".$_POST['sh_addr2']."',

	sh_sido = '".$_POST['sh_sido']."',
	sh_sigungu = '".$_POST['sh_sigungu']."',
	sh_dong = '".$_POST['sh_dong']."',
	sh_x_pos = '".$_POST['sh_x_pos']."',
	sh_y_pos = '".$_POST['sh_y_pos']."',
	sh_bcode = '".$_POST['sh_bcode']."',
	sh_si_idx = '".$_POST['sh_si_idx']."',
	sh_sd_idx = '".$_POST['sh_sd_idx']."'
";


if($_POST['mode'] == "insert"){

	$shop = sql_fetch("select * from rb_shop where shop_id = '".$member['mb_id']."'");
	if($shop['sh_idx']){
		alert("이미 판매자 신청하였습니다.");
	}

	$sh_name_chk = sql_fetch("select * from rb_shop where sh_name = '".$_POST['sh_name']."'  and shop_id != '".$member['mb_id']."'");
	if($sh_name_chk['sh_idx']){
		alert("이미 등록된 미니샵명입니다. 다른 미니샵명을 입력해 주세요.");
	}

	$sql = "insert into rb_shop set
				$sql_common,
				shop_id = '".$member['mb_id']."',
				sh_status = '1',
				sh_regdate = now()
			";
	$sql_q = sql_query($sql);
	$sh_idx = mysql_insert_id();

	goto_url("/member/seller_request_result.php");
}else if($_POST['mode'] == "update1"){

	$shop = sql_fetch("select * from rb_shop where shop_id = '".$member['mb_id']."' and sh_idx = '".$sh_idx."'");
	if(!$shop['sh_idx']){
		alert("판매자 신청을 하지 않으셨습니다.");
	}

	$sh_name_chk = sql_fetch("select * from rb_shop where sh_name = '".$_POST['sh_name']."'  and shop_id != '".$member['mb_id']."'");
	if($sh_name_chk['sh_idx']){
		alert("이미 등록된 미니샵명입니다. 다른 미니샵명을 입력해 주세요.");
	}
	$sql = "update rb_shop set
				$sql_common
				
				where sh_idx = '".$sh_idx."'
			";
	$sql_q = sql_query($sql);



	alert("수정되었습니다.", "/member/seller_request.php");
}
	


alert("잘못된 접근입니다.", "/");

include "../inc/_tail.php";
?>