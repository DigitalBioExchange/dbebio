<?php
$is_icon_menu = 1;
$menu_mode = "w";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

//p_arr($_POST);exit;

$query = $_POST['query'];


if($_GET['mode'] == "delete"){

	$querys = array();
	$querys[] = "ic_type=".$ic_type;
	
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_icon as c1 where c1.ic_idx = '$ic_idx' $search_query");

	if(!$data['ic_idx']) alert("없는 데이터입니다.");

	sql_query("delete from  rb_icon  where ic_idx = '$ic_idx'");

	make_ranking_write('rb_icon', 'ic_sort', 'ic_sort asc, ic_idx asc', "ic_type = '".$ic_type."'");

	alert("삭제되었습니다.", "./icon_list.php?$query");

}else if($_GET['mode'] == "sort1"){

	$querys = array();
	$querys[] = "ic_type=".$ic_type;
	
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_icon as c1 where c1.ic_idx = '$ic_idx' $search_query");

	if(!$data['ic_idx']) alert("없는 데이터입니다.");

	sql_query("update rb_icon set ic_sort = ic_sort + 1 where ic_type = '".$ic_type."'");

	sql_query("update rb_icon set ic_sort = 1 where ic_idx = '$ic_idx'");

	make_ranking_write('rb_icon', 'ic_sort', 'ic_sort asc, ic_idx asc', "ic_type = '".$ic_type."'");

	alert("순서가 변경되었습니다.", "./icon_list.php?$query");

}else if($_GET['mode'] == "sort2"){

	$querys = array();
	$querys[] = "ic_type=".$ic_type;
	
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_icon as c1 where c1.ic_idx = '$ic_idx' $search_query");

	if(!$data['ic_idx']) alert("없는 데이터입니다.");

	sql_query("update rb_icon set ic_sort = ic_sort + 1 where ic_sort = '".($data['ic_sort'] - 1)."' and ic_type = '".$ic_type."'");

	sql_query("update rb_icon set ic_sort = ic_sort - 1 where ic_idx = '$ic_idx'");

	make_ranking_write('rb_icon', 'ic_sort', 'ic_sort asc, ic_idx asc', "ic_type = '".$ic_type."'");

	alert("순서가 변경되었습니다.", "./icon_list.php?$query");

}else if($_GET['mode'] == "sort3"){

	$querys = array();
	$querys[] = "ic_type=".$ic_type;
	
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_icon as c1 where c1.ic_idx = '$ic_idx' $search_query");

	if(!$data['ic_idx']) alert("없는 데이터입니다.");

	sql_query("update rb_icon set ic_sort = ic_sort - 1 where ic_sort = '".($data['ic_sort'] + 1)."' and ic_type = '".$ic_type."'");

	sql_query("update rb_icon set ic_sort = ic_sort + 1 where ic_idx = '$ic_idx'");

	make_ranking_write('rb_icon', 'ic_sort', 'ic_sort asc, ic_idx asc', "ic_type = '".$ic_type."'");

	alert("순서가 변경되었습니다.", "./icon_list.php?$query");
}else if($_GET['mode'] == "sort4"){

	$querys = array();
	$querys[] = "ic_type=".$ic_type;
	
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_icon as c1 where c1.ic_idx = '$ic_idx' $search_query");

	if(!$data['ic_idx']) alert("없는 데이터입니다.");

	sql_query("update rb_icon set ic_sort = 99999999 where ic_idx = '$ic_idx' and ic_type = '".$ic_type."'");

	make_ranking_write('rb_icon', 'ic_sort', 'ic_sort asc, ic_idx asc', "ic_type = '".$ic_type."'");

	alert("순서가 변경되었습니다.", "./icon_list.php?$query");

}

alert("잘못된 접근입니다.", "./icon_list.php?$query");
?>