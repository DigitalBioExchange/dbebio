<?php
$menu_code = "100100";
$menu_mode = "v";


include "../inc/_common.php";
include "../inc/_check.php";

$querys = array();
$querys_page = array();



// GET 값 구해서 각각 필요에 따라 파라미터 만들기
if($_GET['page']){
	$page = $_GET['page'];
}else{
	$page = 1;
}
$querys[] = "page=".$page;

if($_GET['sca'] && $_GET['stx']){
	switch($_GET['sca']){
		default:
			$search_query .= " and ".$_GET['sca']." like '%".$_GET['stx']."%' ";
		break;
	}
}

$querys[] = "sca=".$_GET['sca'];
$querys_page[] = "sca=".$_GET['sca'];
$querys[] = "stx=".$_GET['stx'];
$querys_page[] = "stx=".$_GET['stx'];

if($_GET['mb_status'] != ""){
	$search_query .= " and m.mb_status = '".$_GET['mb_status']."' ";
}
$querys[] = "mb_status=".$_GET['mb_status'];
$querys_page[] = "mb_status=".$_GET['mb_status'];

$query_order = (is_array($querys) && custom_count($querys) > 0) ? implode("&", $querys) : "";
$order_query = "order by m.mb_idx desc";
if($order_by != ""){
	$order_query = " order by $order_by ";
}
$querys[] = "order_by=".$_GET['order_by'];
$querys_page[] = "order_by=".$_GET['order_by'];

$query = (is_array($querys) && custom_count($querys) > 0) ? implode("&", $querys) : "";
$query_page = (is_array($querys_page) && custom_count($querys_page) > 0) ? implode("&", $querys_page) : "";


$sql = "select * from rb_member as m where m.mb_level < ".$_cfg['subadmin_level']." $search_query  $order_query";
$data = sql_list($sql);

$f_name = utf_to_euc(date("Ymd_His").".xls");

header( "Content-type: application/vnd.ms-excel; charset=euc-kr" ); 
header( "Content-Disposition: attachment; filename=$f_name" ); 
header( "Content-Description: PHP4 Generated Data" ); 

ini_set('memory_limit', -1);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="application/vnd.ms-excel;charset=euc-kr">
</head>
<body>
<style>
.txt = {mso-number-format:"\@";}
</style>
<table  border=1>
	<tr>
		<td bgcolor="#dddddd"><?=utf_to_euc('ID')?></td>
		<td bgcolor="#dddddd"><?=utf_to_euc('이름')?></td>
		<td bgcolor="#dddddd"><?=utf_to_euc('거주지역')?></td>
		<td bgcolor="#dddddd"><?=utf_to_euc('휴대폰')?></td>
		<td bgcolor="#dddddd"><?=utf_to_euc('가입일')?></td>
	</tr>
<?
$i=0;
if(custom_count($data) > 0){
foreach($data as $k => $v){
	$row = $v;
?>
	<tr>
		<td class="txt"><?=utf_to_euc($row['mb_id'])?></td>
		<td class="txt"><?=utf_to_euc($row['mb_name'])?></td>
		<td class="txt"><?=utf_to_euc($row['mb_area'])?></td>
		<td class="txt"><?=utf_to_euc($row['mb_tel'])?></td>
		<td class="txt"><?=utf_to_euc(substr($row['mb_regdate'], 0, 10))?></td>
	</tr>
<?
	$i++;
}}
set_time_limit(120);
?>
</table>
</body>
</html>