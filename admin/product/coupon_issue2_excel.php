<?php
$menu_code = "400401";
$menu_mode = "v";


include "../inc/_common.php";
include "../inc/_check.php";

$chk = sql_fetch("select * from rb_coupon_cord_record where cc_idx = '".$_GET['cc_idx']."'");

$sql = "select * from rb_coupon_record as cr left join rb_coupon as c on c.cp_idx = cr.cp_idx where cr.cc_idx = '".$_GET['cc_idx']."'";
$data = sql_list($sql);

$f_name = utf_to_euc($chk['cc_title'].".xls");

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
		<td bgcolor="#dddddd"><?=utf_to_euc('쿠폰명')?></td>
		<td bgcolor="#dddddd"><?=utf_to_euc('발급제목')?></td>
		<td bgcolor="#dddddd"><?=utf_to_euc('쿠폰코드')?></td>
		<td bgcolor="#dddddd"><?=utf_to_euc('발급여부')?></td>
		<td bgcolor="#dddddd"><?=utf_to_euc('발급자ID')?></td>
	</tr>
<?
$i=0;
if(custom_count($data) > 0){
foreach($data as $k => $v){
	$row = $v;
	$is_issue = ($row['mb_id']) ? "발급" : "미발급";
?>
	<tr>
		<td class="txt"><?=utf_to_euc($row['cp_title'])?></td>
		<td class="txt"><?=utf_to_euc($chk['cc_title'])?></td>
		<td class="txt"><?=split_cr_code($row['cr_code'])?></td>
		<td class="txt"><?=utf_to_euc($is_issue)?></td>
		<td class="txt"><?=utf_to_euc($row['mb_id'])?></td>
	</tr>
<?
	$i++;
}}
set_time_limit(120);
?>
</table>
</body>
</html>