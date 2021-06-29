<?php
$menu_code = "400119";
$menu_mode = "w";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

//p_arr($_POST);exit;

$query = $_POST['query'];

$sql_common = "
	ca_name = '".$_POST['ca_name']."'
";

/*
echo $data = json_decode(stripslashes($_POST['cate_data']));
p_arr($data);
echo $data[0]->id;

p_arr($_POST);exit;
*/

if($_POST['mode'] == "insert"){

	$sql = "insert into rb_cate set
				$sql_common
			";
	$sql_q = sql_query($sql);
	$ca_idx = sql_insert_id();

	make_ranking_write('rb_cate', 'ca_sort', 'ca_sort asc, ca_idx asc', "ca_step = '1'");

	alert("카테고리가 추가되었습니다.", "./cate_list.php");
}else if($_POST['mode'] == "delete"){
	

	$data = sql_fetch("select * from rb_cate where ca_idx = '$ca_idx' ");

	if(!$data['ca_idx']) alert("없는 카테고리입니다.");

	sql_query("delete from  rb_cate  where ca_idx = '$ca_idx'");

	$next = sql_list("select * from rb_cate where parent_idx = '".$ca_idx."'");
	sql_query("delete from  rb_cate  where parent_idx = '$ca_idx'");

	for($i=0;$i<custom_count($next);$i++){
		$next1 = sql_list("select * from rb_cate where parent_idx = '".$next[$i]['ca_idx']."'");
		sql_query("delete from  rb_cate  where parent_idx = '".$next[$i]['ca_idx']."'");

		for($j=0;$j<custom_count($next1);$j++){
			$next2 = sql_list("select * from rb_cate where parent_idx = '".$next1[$j]['ca_idx']."'");
			sql_query("delete from  rb_cate  where parent_idx = '".$next1[$j]['ca_idx']."'");

			for($k=0;$k<custom_count($next2);$k++){
				sql_query("delete from  rb_cate  where parent_idx = '".$next2[$j]['ca_idx']."'");
			}

		}

	}

	alert("카테고리가 삭제되었습니다.", "./cate_list.php");

}else if($_POST['mode'] == "copy"){
	

	$data = sql_fetch("select * from rb_cate where ca_idx = '$ca_idx' ");
	if(!$data['ca_idx']) alert("없는 카테고리입니다.");

	$sql = "insert into rb_cate set
				ca_name = '".$data['ca_name']."'
			";
	$sql_q = sql_query($sql);
	$new_ca_idx = sql_insert_id();

	make_ranking_write('rb_cate', 'ca_sort', 'ca_sort asc, ca_idx asc', "ca_step = '1'");


	$next = sql_list("select * from rb_cate where parent_idx = '".$ca_idx."'");
	$parent_idx = $new_ca_idx;

	for($i=0;$i<custom_count($next);$i++){
		$sql = "insert into rb_cate set
					ca_step = '2',
					parent_idx = '$parent_idx',
					ca_name = '".$next[$i]['ca_name']."',
					ca_sort = '".$next[$i]['ca_sort']."'
				";
		$sql_q = sql_query($sql);	
		$new_ca_idx1 = sql_insert_id();

		$next1 = sql_list("select * from rb_cate where parent_idx = '".$next[$i]['ca_idx']."'");
		$parent_idx1 = $new_ca_idx1;


		for($j=0;$j<custom_count($next1);$j++){

			$sql = "insert into rb_cate set
						ca_step = '3',
						parent_idx = '$parent_idx1',
						ca_name = '".$next1[$j]['ca_name']."',
						ca_sort = '".$next1[$j]['ca_sort']."'
					";
			$sql_q = sql_query($sql);	
			$new_ca_idx2 = sql_insert_id();

			$next2 = sql_list("select * from rb_cate where parent_idx = '".$next1[$j]['ca_idx']."'");
			$parent_idx2 = $new_ca_idx2;

			for($k=0;$k<custom_count($next2);$k++){
				$sql = "insert into rb_cate set
							ca_step = '4',
							parent_idx = '$parent_idx2',
							ca_name = '".$next2[$k]['ca_name']."',
							ca_sort = '".$next2[$k]['ca_sort']."'
						";
				$sql_q = sql_query($sql);	
			}

		}

	}

	alert("카테고리가 복사되었습니다.", "./cate_list.php");

}

alert("잘못된 접근입니다.", "./cate_list.php");
?>