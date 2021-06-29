<?php
include "./inc/_common.php";

if($is_admin){
	goto_url("/admin/main/main.php");
}else{
	goto_url("/admin/login.php");
}
?> 
