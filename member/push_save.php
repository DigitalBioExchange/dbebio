<?php
include "../inc/_common.php";
include "../inc/_head.php";

goto_login();

$sql_common = "
	mb_push = '".$_POST['mb_push']."'
";


	$sql = "update rb_member set
				$sql_common
				
				where mb_idx = '".$member['mb_idx']."'
			";
	$sql_q = sql_query($sql);

	c_db2();

	sql_query("update member set recv_on = '".$_POST['mb_push']."' , last_time =now() where tag = '$mb_id' and domain = '$sv_code' and removed = 0");


	alert("푸쉬(알림)설정이 저장되었습니다.", "/member/push.php");

include "../inc/_tail.php";
?>