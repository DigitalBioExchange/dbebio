<?php
include "./inc/_common.php";
include "./inc/_head.php";

session_destroy();

goto_url("/admin/login.php");
?>