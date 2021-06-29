<?php
$menu_code = "400111";
$menu_mode = "w";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

//p_arr($_POST);exit;

$query = $_POST['query'];

$sql_common = "
	c1_name = '".$_POST['c1_name']."',
	c1_sort = '".$_POST['c1_sort']."'
";


echo $data = json_decode(stripslashes($_POST['cate_data']));
p_arr($data);
echo $data[0]->id;

p_arr($_POST);exit;
if($_POST['mode'] == "insert"){

	$sql = "insert into rb_cate1 set
				$sql_common
			";
	$sql_q = sql_query($sql);
	$c1_idx = mysql_insert_id();

	make_ranking_write('rb_cate1', 'c1_sort', 'c1_sort asc, c1_idx asc');

	alert("카테고리가 추가되었습니다.", "./cate1_list.php?$query");
}else if($_POST['mode'] == "update"){

	$data = sql_fetch("select * from rb_cate1 as c1 where c1.c1_idx = '$c1_idx' $search_query");

	if(!$data['c1_idx']) alert("없는 카테고리입니다.");


	$sql = "update rb_cate1 set
				$sql_common
				where c1_idx = '".$_POST['c1_idx']."'
			";
	$sql_q = sql_query($sql);
	$c1_idx = $_POST['c1_idx'];

	make_ranking_write('rb_cate1', 'c1_sort', 'c1_sort asc, c1_idx asc');

	alert("카테고리가 수정되었습니다.", "./cate1_view.php?c1_idx=".$_POST['c1_idx']."&$query");
}else if($_GET['mode'] == "delete"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	
	$query = (is_array($querys) && custom_count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_cate1 as c1 where c1.c1_idx = '$c1_idx' $search_query");

	if(!$data['c1_idx']) alert("없는 카테고리입니다.");

	sql_query("delete from  rb_cate1  where c1_idx = '$c1_idx'");

	make_ranking_write('rb_cate1', 'c1_sort', 'c1_sort asc, c1_idx asc');

	alert("카테고리가 삭제되었습니다.", "./cate1_list.php?$query");

}else if($_GET['mode'] == "sort1"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	
	$query = (is_array($querys) && custom_count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_cate1 as c1 where c1.c1_idx = '$c1_idx' $search_query");

	if(!$data['c1_idx']) alert("없는 카테고리입니다.");

	sql_query("update rb_cate1 set c1_sort = c1_sort + 1 where 1");

	sql_query("update rb_cate1 set c1_sort = 1 where c1_idx = '$c1_idx'");

	make_ranking_write('rb_cate1', 'c1_sort', 'c1_sort asc, c1_idx asc');

	alert("순서가 변경되었습니다.", "./cate1_list.php?$query");

}else if($_GET['mode'] == "sort2"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	
	$query = (is_array($querys) && custom_count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_cate1 as c1 where c1.c1_idx = '$c1_idx' $search_query");

	if(!$data['c1_idx']) alert("없는 카테고리입니다.");

	sql_query("update rb_cate1 set c1_sort = c1_sort + 1 where c1_sort = '".($data['c1_sort'] - 1)."' ");

	sql_query("update rb_cate1 set c1_sort = c1_sort - 1 where c1_idx = '$c1_idx'");

	make_ranking_write('rb_cate1', 'c1_sort', 'c1_sort asc, c1_idx asc');

	alert("순서가 변경되었습니다.", "./cate1_list.php?$query");

}else if($_GET['mode'] == "sort3"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	
	$query = (is_array($querys) && custom_count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_cate1 as c1 where c1.c1_idx = '$c1_idx' $search_query");

	if(!$data['c1_idx']) alert("없는 카테고리입니다.");

	sql_query("update rb_cate1 set c1_sort = c1_sort - 1 where c1_sort = '".($data['c1_sort'] + 1)."' ");

	sql_query("update rb_cate1 set c1_sort = c1_sort + 1 where c1_idx = '$c1_idx'");

	make_ranking_write('rb_cate1', 'c1_sort', 'c1_sort asc, c1_idx asc');

	alert("순서가 변경되었습니다.", "./cate1_list.php?$query");
}else if($_GET['mode'] == "sort4"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	
	$query = (is_array($querys) && custom_count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_cate1 as c1 where c1.c1_idx = '$c1_idx' $search_query");

	if(!$data['c1_idx']) alert("없는 카테고리입니다.");

	sql_query("update rb_cate1 set c1_sort = 99999999 where c1_idx = '$c1_idx'");

	make_ranking_write('rb_cate1', 'c1_sort', 'c1_sort asc, c1_idx asc');

	alert("순서가 변경되었습니다.", "./cate1_list.php?$query");

}

alert("잘못된 접근입니다.", "./cate1_list.php?$query");
?>