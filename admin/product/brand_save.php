<?php
$menu_code = "400150";
$menu_mode = "w";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

//p_arr($_POST);exit;

$query = $_POST['query'];

$sql_common = "
	br_name = '".$_POST['br_name']."',
	br_exp = '".$_POST['br_exp']."',
	br_type = '".$_POST['br_type']."',
	br_contents = '".$_POST['br_contents']."'
";

//이미지체크
$field_arr = array("br_img1");
foreach($field_arr as $k => $v){
	if($_FILES[$v]['tmp_name']){
		$timg = @getimagesize($_FILES[$v]['tmp_name']);
		if($timg[2] != 1 && $timg[2] != 2 && $timg[2] != 3) alert("jpg, gif, png 파일만 업로드 가능합니다.");
	}
}

if($_POST['mode'] == "insert"){

	$sql = "insert into rb_brand set
				$sql_common
			";
	$sql_q = sql_query($sql);
	$br_idx = sql_insert_id();

	//파일저장
	foreach($field_arr as $k => $v){
		if($_FILES[$v]['tmp_name']){
			$src = $_FILES[$v]['tmp_name'];
			$ext = get_file_ext($_FILES[$v]['name']);
			$tgt_name = md5(uniqid(rand(), TRUE)).".".$ext;
			$org_name = $_FILES[$v]['name'];
			$tgt = $_cfg['web_home'].$_cfg['data_dir']."/files/".$tgt_name;

			Chk_exif_WH($src, $tgt);

			sql_query("update rb_brand set $v = '$tgt_name', {$v}_org = '$org_name' where br_idx = '$br_idx'");

		}
	}

	make_ranking_write('rb_brand', 'br_sort', 'br_sort asc, br_idx asc');

	alert("브랜드가 추가되었습니다.", "./brand_list.php?$query");
}else if($_POST['mode'] == "update"){

	$data = sql_fetch("select * from rb_brand as mn where mn.br_idx = '$br_idx' $search_query");

	if(!$data['br_idx']) alert("없는 브랜드입니다.");


	$sql = "update rb_brand set
				$sql_common
				where br_idx = '".$_POST['br_idx']."'
			";
	$sql_q = sql_query($sql);
	$br_idx = $_POST['br_idx'];

	//파일삭제
	foreach($field_arr as $k => $v){
		if($_FILES[$v]['tmp_name']){
			sql_query("update rb_brand set $v = '', {$v}_org = '' where br_idx = '$br_idx'");
			@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/".$data[$v]);
		}
	}

	//파일저장
	foreach($field_arr as $k => $v){
		if($_FILES[$v]['tmp_name']){
			$src = $_FILES[$v]['tmp_name'];
			$ext = get_file_ext($_FILES[$v]['name']);
			$tgt_name = md5(uniqid(rand(), TRUE)).".".$ext;
			$org_name = $_FILES[$v]['name'];
			$tgt = $_cfg['web_home'].$_cfg['data_dir']."/files/".$tgt_name;

			Chk_exif_WH($src, $tgt);

			sql_query("update rb_brand set $v = '$tgt_name', {$v}_org = '$org_name' where br_idx = '$br_idx'");

		}
	}



	alert("브랜드가 수정되었습니다.", "./brand_view.php?br_idx=".$_POST['br_idx']."&$query");
}else if($_GET['mode'] == "delete"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_brand as mn where mn.br_idx = '$br_idx' $search_query");

	if(!$data['br_idx']) alert("없는 브랜드입니다.");

	//파일삭제
	foreach($field_arr as $k => $v){
		@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/".$data[$v]);
	}


	sql_query("delete from  rb_brand  where br_idx = '$br_idx'");

	make_ranking_write('rb_brand', 'br_sort', 'br_sort asc, br_idx asc');

	alert("브랜드가 삭제되었습니다.", "./brand_list.php?$query");

}else if($_GET['mode'] == "sort1"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_brand as mn where mn.br_idx = '$br_idx' $search_query");

	if(!$data['br_idx']) alert("없는 브랜드입니다.");

	sql_query("update rb_brand set br_sort = br_sort + 1 where 1");

	sql_query("update rb_brand set br_sort = 1 where br_idx = '$br_idx'");

	make_ranking_write('rb_brand', 'br_sort', 'br_sort asc, br_idx asc');

	alert("순서가 변경되었습니다.", "./brand_list.php?$query");

}else if($_GET['mode'] == "sort2"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_brand as mn where mn.br_idx = '$br_idx' $search_query");

	if(!$data['br_idx']) alert("없는 브랜드입니다.");

	sql_query("update rb_brand set br_sort = br_sort + 1 where br_sort = '".($data['br_sort'] - 1)."' ");

	sql_query("update rb_brand set br_sort = br_sort - 1 where br_idx = '$br_idx'");

	make_ranking_write('rb_brand', 'br_sort', 'br_sort asc, br_idx asc');

	alert("순서가 변경되었습니다.", "./brand_list.php?$query");

}else if($_GET['mode'] == "sort3"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_brand as mn where mn.br_idx = '$br_idx' $search_query");

	if(!$data['br_idx']) alert("없는 브랜드입니다.");

	sql_query("update rb_brand set br_sort = br_sort - 1 where br_sort = '".($data['br_sort'] + 1)."' ");

	sql_query("update rb_brand set br_sort = br_sort + 1 where br_idx = '$br_idx'");

	make_ranking_write('rb_brand', 'br_sort', 'br_sort asc, br_idx asc');

	alert("순서가 변경되었습니다.", "./brand_list.php?$query");
}else if($_GET['mode'] == "sort4"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_brand as mn where mn.br_idx = '$br_idx' $search_query");

	if(!$data['br_idx']) alert("없는 브랜드입니다.");

	sql_query("update rb_brand set br_sort = 99999999 where br_idx = '$br_idx'");

	make_ranking_write('rb_brand', 'br_sort', 'br_sort asc, br_idx asc');

	alert("순서가 변경되었습니다.", "./brand_list.php?$query");

}

alert("잘못된 접근입니다.", "./brand_list.php?$query");
?>