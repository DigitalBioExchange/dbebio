<?php
include "../inc/_common.php";
include "../inc/_head.php";
goto_login();

$sql_common = "
	ad_name = '".$_POST['ad_name']."',
	ad_tel = '".$_POST['ad_tel']."',
	ad_tel2 = '".$_POST['ad_tel2']."',
	ad_zip = '".$_POST['ad_zip']."',
	ad_addr1 = '".$_POST['ad_addr1']."',
	ad_addr2 = '".$_POST['ad_addr2']."'
";

//p_arr($_POST);echo "ok";exit;


if($_POST['mode'] == "insert"){

	$sql = "insert into rb_address set
				$sql_common,
				mb_id = '".$member['mb_id']."'
			";
	$sql_q = sql_query($sql);


	alert("새로운 배송지가 추가되었습니다.", "/member/address.php");
}else if($_POST['mode'] == "update"){

	$sql = "update rb_address set
				$sql_common
				
				where ad_idx = '".$ad_idx."'
			";
	$sql_q = sql_query($sql);



	alert("수정되었습니다.", "/member/address.php");
}else if($_POST['mode'] == "default"){

	sql_query("update rb_address set ad_default = 0 where mb_id = '".$member['mb_id']."' and ad_idx != '".$ad_idx."'");
	$sql = "update rb_address set ad_default = 1 where mb_id = '".$member['mb_id']."' and ad_idx = '".$ad_idx."' ";
	$sql_q = sql_query($sql);

	alert("기본배송지로 설정되었습니다.", "/member/address.php");
}else if($_POST['mode'] == "delete"){

	sql_query("delete from rb_address where mb_id = '".$member['mb_id']."' and ad_idx = '".$ad_idx."'");
	alert("삭제되었습니다.", "/member/address.php");
}
	
//p_arr($_POST);echo "error";exit;

alert("잘못된 접근입니다.", "/");

include "../inc/_tail.php";
?>