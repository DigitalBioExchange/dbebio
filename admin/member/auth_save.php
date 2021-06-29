<?php
$menu_code = "100599";
$menu_mode = "w";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

$query = $_POST['query'];

$comma1 = "";
$comma2 = "";
$_POST['auth_v'] = "";
$_POST['auth_w'] = "";
for($i=0;$i<count($_cfg['menu_data']);$i++){
	if(strlen($_cfg['menu_data'][$i]['menu_code']) == 6){
		if($_POST[$_cfg['menu_data'][$i]['menu_code']."_v"]){
			$_POST['auth_v'] .= $comma1.$_cfg['menu_data'][$i]['menu_code'];
			$comma1 = ",";
		}
		if($_POST[$_cfg['menu_data'][$i]['menu_code']."_w"]){
			$_POST['auth_w'] .= $comma2.$_cfg['menu_data'][$i]['menu_code'];
			$comma2 = ",";
		}
	}
}

$sql_common = "
	auth_v = '".$_POST['auth_v']."',
	auth_w = '".$_POST['auth_w']."'
";

if($_POST['mode'] == "update" && $_POST['mb_idx']){

	$sql = "update rb_member set
				$sql_common
			where mb_idx = '".$_POST['mb_idx']."'
			";
	$sql_q = sql_query($sql);


	alert("권한이 수정 되었습니다.", "./auth_list.php?$query");
}

alert("잘못된 접근입니다.", "./auth_list.php?$query");
?>