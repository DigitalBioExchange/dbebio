<?php
$menu_code = "400113";
$menu_mode = "w";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

//p_arr($_POST);exit;

$query = $_POST['query'];

$sql_common = "
	c2_idx = '".$_POST['c2_idx']."',
	c3_name = '".$_POST['c3_name']."',
	c3_sort = '".$_POST['c3_sort']."'
";



if($_POST['mode'] == "insert"){

	$sql = "insert into rb_cate3 set
				$sql_common
			";
	$sql_q = sql_query($sql);
	$c3_idx = mysql_insert_id();

	make_ranking_write('rb_cate3', 'c3_sort', 'c3_sort asc, c3_idx asc', "c2_idx = '".$_POST['c2_idx']."'");

	alert("카테고리가 추가되었습니다.", "./cate3_list.php?$query");
}else if($_POST['mode'] == "update"){

	$data = sql_fetch("select * from rb_cate3 as c3 where c3.c3_idx = '$c3_idx' $search_query");

	if(!$data['c3_idx']) alert("없는 카테고리입니다.");


	$sql = "update rb_cate3 set
				$sql_common
				where c3_idx = '".$_POST['c3_idx']."'
			";
	$sql_q = sql_query($sql);
	$c3_idx = $_POST['c3_idx'];

	make_ranking_write('rb_cate3', 'c3_sort', 'c3_sort asc, c3_idx asc', "c2_idx = '".$_POST['c2_idx']."'");

	alert("카테고리가 수정되었습니다.", "./cate3_view.php?c3_idx=".$_POST['c3_idx']."&$query");
}else if($_GET['mode'] == "delete"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	$querys[] = "c2_idx=".$_GET['c2_idx'];
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_cate3 as c3 where c3.c3_idx = '$c3_idx' $search_query");

	if(!$data['c3_idx']) alert("없는 카테고리입니다.");

	sql_query("delete from  rb_cate3  where c3_idx = '$c3_idx'");

	make_ranking_write('rb_cate3', 'c3_sort', 'c3_sort asc, c3_idx asc', "c2_idx = '".$data['c2_idx']."'");

	alert("카테고리가 삭제되었습니다.", "./cate3_list.php?$query");

}else if($_GET['mode'] == "sort1"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	$querys[] = "c2_idx=".$_GET['c2_idx'];
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_cate3 as c3 where c3.c3_idx = '$c3_idx' $search_query");

	if(!$data['c3_idx']) alert("없는 카테고리입니다.");

	sql_query("update rb_cate3 set c3_sort = c3_sort + 1 where c2_idx = '".$data['c2_idx']."'");

	sql_query("update rb_cate3 set c3_sort = 1 where c3_idx = '$c3_idx'");

	make_ranking_write('rb_cate3', 'c3_sort', 'c3_sort asc, c3_idx asc', "c2_idx = '".$data['c2_idx']."'");

	alert("순서가 변경되었습니다.", "./cate3_list.php?$query");

}else if($_GET['mode'] == "sort2"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	$querys[] = "c2_idx=".$_GET['c2_idx'];
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_cate3 as c3 where c3.c3_idx = '$c3_idx' $search_query");

	if(!$data['c3_idx']) alert("없는 카테고리입니다.");

	sql_query("update rb_cate3 set c3_sort = c3_sort + 1 where c3_sort = '".($data['c3_sort'] - 1)."' and c2_idx = '".$data['c2_idx']."'");

	sql_query("update rb_cate3 set c3_sort = c3_sort - 1 where c3_idx = '$c3_idx'");

	make_ranking_write('rb_cate3', 'c3_sort', 'c3_sort asc, c3_idx asc', "c2_idx = '".$data['c2_idx']."'");

	alert("순서가 변경되었습니다.", "./cate3_list.php?$query");

}else if($_GET['mode'] == "sort3"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	$querys[] = "c2_idx=".$_GET['c2_idx'];
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_cate3 as c3 where c3.c3_idx = '$c3_idx' $search_query");

	if(!$data['c3_idx']) alert("없는 카테고리입니다.");

	sql_query("update rb_cate3 set c3_sort = c3_sort - 1 where c3_sort = '".($data['c3_sort'] + 1)."' and c2_idx = '".$data['c2_idx']."' ");

	sql_query("update rb_cate3 set c3_sort = c3_sort + 1 where c3_idx = '$c3_idx'");

	make_ranking_write('rb_cate3', 'c3_sort', 'c3_sort asc, c3_idx asc', "c2_idx = '".$data['c2_idx']."'");

	alert("순서가 변경되었습니다.", "./cate3_list.php?$query");
}else if($_GET['mode'] == "sort4"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	$querys[] = "c2_idx=".$_GET['c2_idx'];
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_cate3 as c3 where c3.c3_idx = '$c3_idx' $search_query");

	if(!$data['c3_idx']) alert("없는 카테고리입니다.");

	sql_query("update rb_cate3 set c3_sort = 99999999 where c3_idx = '$c3_idx'");

	make_ranking_write('rb_cate3', 'c3_sort', 'c3_sort asc, c3_idx asc', "c2_idx = '".$data['c2_idx']."'");

	alert("순서가 변경되었습니다.", "./cate3_list.php?$query");

}

alert("잘못된 접근입니다.", "./cate3_list.php?$query");
?>