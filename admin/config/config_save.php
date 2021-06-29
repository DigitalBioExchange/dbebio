<?php
$menu_code = "110100";
$menu_mode = "w";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

$query = $_POST['query'];

$sql_common = "
	cf_free = '".$_POST['cf_free']."',
	cf_stipulation = '".$_POST['cf_stipulation']."',
	cf_privacy = '".$_POST['cf_privacy']."',
	cf_master_wallet = '".$_POST['cf_master_wallet']."',
	cf_coin_server_web = '".$_POST['cf_coin_server_web']."',
	cf_coin_server_mobile = '".$_POST['cf_coin_server_mobile']."'
";

$sql_common2 = "
	cf_delivery_type2 = '".$_POST['cf_delivery_type2']."',
	cf_delivery_free_amount = '".$_POST['cf_delivery_free_amount']."',
	cf_delivery_amount = '".$_POST['cf_delivery_amount']."',
	cf_delivery_contents = '".$_POST['cf_delivery_contents']."',
	cf_de_com = '".$_POST['cf_de_com']."'
";

if($_POST['mode'] == "update"){

	$sql = "update rb_config set
				$sql_common
			";
	$sql_q = sql_query($sql);

	$sql = "update rb_shop_config set
				$sql_common2 where 1
			";
	$sql_q = sql_query($sql);

	alert("환경설정이 저장되었습니다.", "./config_modify.php?$query");
}

alert("잘못된 접근입니다.", "./config_modify.php?$query");
?>