<?php
$menu_code = "400112";
$menu_mode = "w";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

//p_arr($_POST);exit;

$query = $_POST['query'];

$sql_common = "
	c1_idx = '".$_POST['c1_idx']."',
	c2_name = '".$_POST['c2_name']."',
	c2_sort = '".$_POST['c2_sort']."'
";



if($_POST['mode'] == "insert"){

	$sql = "insert into rb_cate2 set
				$sql_common
			";
	$sql_q = sql_query($sql);
	$c2_idx = mysql_insert_id();

	make_ranking_write('rb_cate2', 'c2_sort', 'c2_sort asc, c2_idx asc', "c1_idx = '".$_POST['c1_idx']."'");

	alert("카테고리가 추가되었습니다.", "./cate2_list.php?$query");
}else if($_POST['mode'] == "update"){

	$data = sql_fetch("select * from rb_cate2 as c2 where c2.c2_idx = '$c2_idx' $search_query");

	if(!$data['c2_idx']) alert("없는 카테고리입니다.");


	$sql = "update rb_cate2 set
				$sql_common
				where c2_idx = '".$_POST['c2_idx']."'
			";
	$sql_q = sql_query($sql);
	$c2_idx = $_POST['c2_idx'];

	make_ranking_write('rb_cate2', 'c2_sort', 'c2_sort asc, c2_idx asc', "c1_idx = '".$_POST['c1_idx']."'");

	alert("카테고리가 수정되었습니다.", "./cate2_view.php?c2_idx=".$_POST['c2_idx']."&$query");
}else if($_GET['mode'] == "delete"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	$querys[] = "c1_idx=".$_GET['c1_idx'];
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_cate2 as c2 where c2.c2_idx = '$c2_idx' $search_query");

	if(!$data['c2_idx']) alert("없는 카테고리입니다.");

	sql_query("delete from  rb_cate2  where c2_idx = '$c2_idx'");

	make_ranking_write('rb_cate2', 'c2_sort', 'c2_sort asc, c2_idx asc', "c1_idx = '".$data['c1_idx']."'");

	alert("카테고리가 삭제되었습니다.", "./cate2_list.php?$query");

}else if($_GET['mode'] == "sort1"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	$querys[] = "c1_idx=".$_GET['c1_idx'];
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_cate2 as c2 where c2.c2_idx = '$c2_idx' $search_query");

	if(!$data['c2_idx']) alert("없는 카테고리입니다.");

	sql_query("update rb_cate2 set c2_sort = c2_sort + 1 where c1_idx = '".$data['c1_idx']."'");

	sql_query("update rb_cate2 set c2_sort = 1 where c2_idx = '$c2_idx'");

	make_ranking_write('rb_cate2', 'c2_sort', 'c2_sort asc, c2_idx asc', "c1_idx = '".$data['c1_idx']."'");

	alert("순서가 변경되었습니다.", "./cate2_list.php?$query");

}else if($_GET['mode'] == "sort2"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	$querys[] = "c1_idx=".$_GET['c1_idx'];
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_cate2 as c2 where c2.c2_idx = '$c2_idx' $search_query");

	if(!$data['c2_idx']) alert("없는 카테고리입니다.");

	sql_query("update rb_cate2 set c2_sort = c2_sort + 1 where c2_sort = '".($data['c2_sort'] - 1)."' and c1_idx = '".$data['c1_idx']."'");

	sql_query("update rb_cate2 set c2_sort = c2_sort - 1 where c2_idx = '$c2_idx'");

	make_ranking_write('rb_cate2', 'c2_sort', 'c2_sort asc, c2_idx asc', "c1_idx = '".$data['c1_idx']."'");

	alert("순서가 변경되었습니다.", "./cate2_list.php?$query");

}else if($_GET['mode'] == "sort3"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	$querys[] = "c1_idx=".$_GET['c1_idx'];
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_cate2 as c2 where c2.c2_idx = '$c2_idx' $search_query");

	if(!$data['c2_idx']) alert("없는 카테고리입니다.");

	sql_query("update rb_cate2 set c2_sort = c2_sort - 1 where c2_sort = '".($data['c2_sort'] + 1)."' and c1_idx = '".$data['c1_idx']."' ");

	sql_query("update rb_cate2 set c2_sort = c2_sort + 1 where c2_idx = '$c2_idx'");

	make_ranking_write('rb_cate2', 'c2_sort', 'c2_sort asc, c2_idx asc', "c1_idx = '".$data['c1_idx']."'");

	alert("순서가 변경되었습니다.", "./cate2_list.php?$query");
}else if($_GET['mode'] == "sort4"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	$querys[] = "c1_idx=".$_GET['c1_idx'];
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_cate2 as c2 where c2.c2_idx = '$c2_idx' $search_query");

	if(!$data['c2_idx']) alert("없는 카테고리입니다.");

	sql_query("update rb_cate2 set c2_sort = 99999999 where c2_idx = '$c2_idx'");

	make_ranking_write('rb_cate2', 'c2_sort', 'c2_sort asc, c2_idx asc', "c1_idx = '".$data['c1_idx']."'");

	alert("순서가 변경되었습니다.", "./cate2_list.php?$query");

}

alert("잘못된 접근입니다.", "./cate2_list.php?$query");
?>