<?php
include "../inc/_common.php";
include "../inc/_head.php";

goto_login();

if($is_admin){
	alert("관리자는 관리자페이지에서만 수정이 가능합니다.." , "/index.php");
}

$l_menu = 7;
$l_menu = 4;


$chk = sql_fetch("select * from rb_member where mb_pass = password('".$_POST['mb_pass']."') and mb_id = '".$member['mb_id']."'");
if(!$chk['mb_id']){
	alert("비밀번호가 정확하지 않습니다.");
}

$_SESSION['my_pass_ok'] = 1;

goto_url("/member/my_info.php");

include "../inc/_tail.php";
?>