<?php
$menu_code = "100600";
$menu_mode = "w";

$limit_access = "";
$limit_access_level = "worker";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

$query = $_POST['query'];

// $sql_common = "
// 	mb_name = '".$_POST['mb_name']."',
// 	mb_email = '".$_POST['mb_email']."',
// 	mb_otp_use = '".$_POST['mb_otp_use']."'
// ";

$sql_common = "
	mb_name = '".$_POST['mb_name']."',
	mb_email = '".$_POST['mb_email']."'
";

if($_POST['mode'] == "update"){

	if($_POST['mb_pass']) $sql_pass = ", mb_pass = password('".$_POST['mb_pass']."')";

	$sql = "update rb_member set
				$sql_common
				$sql_pass
			where mb_idx = '".$member['mb_idx']."'
			";
	$sql_q = sql_query($sql);

	alert("내정보가 수정 되었습니다.", "./myinfo.php?$query");
}

alert("잘못된 접근입니다.", "./myinfo.php?$query");
?>