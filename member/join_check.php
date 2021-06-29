<?php
$menu_num = 0;
include "../inc/_common.php";
include "../inc/_head.php";

already_logged();


if(!$agree_1){
	alert("이용약관에 동의하셔야합니다.");
}

if(!$agree_2){
	alert("개인정보처리방침에 동의하셔야합니다.");
}

set_session("join_ok" , 1);

$_POST['mb_level'] = (in_array($_POST['mb_level'], array(1, $_cfg['seller_level']))) ? $_POST['mb_level'] : 1;
goto_url("/member/join_form.php?mb_level=".$_POST['mb_level']);

include "../inc/_tail.php";
?>