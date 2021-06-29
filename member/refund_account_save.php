<?php
include "../inc/_common.php";
include "../inc/_head.php";
goto_login();

$sql_common = "
	mb_refund_bank = '".$_POST['mb_refund_bank']."',
	mb_refund_account = '".$_POST['mb_refund_account']."',
	mb_refund_account_owner = '".$_POST['mb_refund_account_owner']."'
";

//p_arr($_POST);echo "ok";exit;


$sql = "update rb_member set
			$sql_common
			
			where mb_id = '".$member['mb_id']."'
		";
$sql_q = sql_query($sql);



alert("환불계좌가 등록되었습니다.", "/member/refund_account.php");

include "../inc/_tail.php";
?>