<?php
include "../inc/_common.php";
include "../inc/_head.php";

already_logged();


$chk = sql_fetch("select * from rb_member where mb_name = '".$_POST['mb_name']."' and mb_hp = '".$_POST['mb_hp']."'");
$_SESSION['find_idpw_id'] = $chk['mb_id'];

goto_url("/member/find_id_result.php");

include "../inc/_tail.php";
?>