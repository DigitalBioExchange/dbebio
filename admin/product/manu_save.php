<?php
$menu_code = "400150";
$menu_mode = "w";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

//p_arr($_POST);exit;

$query = $_POST[query];

$sql_common = "
	mn_name = '".$_POST[mn_name]."',
	mn_sort = '".$_POST[mn_sort]."'
";



if($_POST[mode] == "insert"){

	$sql = "insert into rb_manu set
				$sql_common
			";
	$sql_q = sql_query($sql);
	$mn_idx = mysql_insert_id();

	make_ranking_write('rb_manu', 'mn_sort', 'mn_sort asc, mn_idx asc');

	alert("제조사가 추가되었습니다.", "./manu_list.php?$query");
}else if($_POST[mode] == "update"){

	$data = sql_fetch("select * from rb_manu as mn where mn.mn_idx = '$mn_idx' $search_query");

	if(!$data[mn_idx]) alert("없는 제조사입니다.");


	$sql = "update rb_manu set
				$sql_common
				where mn_idx = '$_POST[mn_idx]'
			";
	$sql_q = sql_query($sql);
	$mn_idx = $_POST[mn_idx];

	make_ranking_write('rb_manu', 'mn_sort', 'mn_sort asc, mn_idx asc');

	alert("제조사가 수정되었습니다.", "./manu_view.php?mn_idx=$_POST[mn_idx]&$query");
}else if($_GET[mode] == "delete"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET[sca];
	$querys[] = "stx=".$_GET[stx];
	
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_manu as mn where mn.mn_idx = '$mn_idx' $search_query");

	if(!$data[mn_idx]) alert("없는 제조사입니다.");

	sql_query("delete from  rb_manu  where mn_idx = '$mn_idx'");

	make_ranking_write('rb_manu', 'mn_sort', 'mn_sort asc, mn_idx asc');

	alert("제조사가 삭제되었습니다.", "./manu_list.php?$query");

}else if($_GET[mode] == "sort1"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET[sca];
	$querys[] = "stx=".$_GET[stx];
	
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_manu as mn where mn.mn_idx = '$mn_idx' $search_query");

	if(!$data[mn_idx]) alert("없는 제조사입니다.");

	sql_query("update rb_manu set mn_sort = mn_sort + 1 where 1");

	sql_query("update rb_manu set mn_sort = 1 where mn_idx = '$mn_idx'");

	make_ranking_write('rb_manu', 'mn_sort', 'mn_sort asc, mn_idx asc');

	alert("순서가 변경되었습니다.", "./manu_list.php?$query");

}else if($_GET[mode] == "sort2"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET[sca];
	$querys[] = "stx=".$_GET[stx];
	
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_manu as mn where mn.mn_idx = '$mn_idx' $search_query");

	if(!$data[mn_idx]) alert("없는 제조사입니다.");

	sql_query("update rb_manu set mn_sort = mn_sort + 1 where mn_sort = '".($data[mn_sort] - 1)."' ");

	sql_query("update rb_manu set mn_sort = mn_sort - 1 where mn_idx = '$mn_idx'");

	make_ranking_write('rb_manu', 'mn_sort', 'mn_sort asc, mn_idx asc');

	alert("순서가 변경되었습니다.", "./manu_list.php?$query");

}else if($_GET[mode] == "sort3"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET[sca];
	$querys[] = "stx=".$_GET[stx];
	
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_manu as mn where mn.mn_idx = '$mn_idx' $search_query");

	if(!$data[mn_idx]) alert("없는 제조사입니다.");

	sql_query("update rb_manu set mn_sort = mn_sort - 1 where mn_sort = '".($data[mn_sort] + 1)."' ");

	sql_query("update rb_manu set mn_sort = mn_sort + 1 where mn_idx = '$mn_idx'");

	make_ranking_write('rb_manu', 'mn_sort', 'mn_sort asc, mn_idx asc');

	alert("순서가 변경되었습니다.", "./manu_list.php?$query");
}else if($_GET[mode] == "sort4"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET[sca];
	$querys[] = "stx=".$_GET[stx];
	
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_manu as mn where mn.mn_idx = '$mn_idx' $search_query");

	if(!$data[mn_idx]) alert("없는 제조사입니다.");

	sql_query("update rb_manu set mn_sort = 99999999 where mn_idx = '$mn_idx'");

	make_ranking_write('rb_manu', 'mn_sort', 'mn_sort asc, mn_idx asc');

	alert("순서가 변경되었습니다.", "./manu_list.php?$query");

}

alert("잘못된 접근입니다.", "./manu_list.php?$query");
?>