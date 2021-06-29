<?php
header("Content-type: text/html; charset=UTF-8");
include "../inc/_common.php";


$_SESSION['ss_od_email'] = $_POST['od_email'];
$_SESSION['ss_od_tel'] = $_POST['od_tel'];
$_SESSION['ss_od_pass'] = $_POST['od_pass'];

goto_url("/shop/order_list.php");
?>