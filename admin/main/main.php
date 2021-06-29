<?php
include "../inc/_common.php";
include "../inc/_head.php";


//exit; 
if($home_link)
	goto_url($home_link);
else
	alert("권한이 없습니다.");
?>