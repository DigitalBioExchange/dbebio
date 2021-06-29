<?php
$menu_code = "400501";
$menu_mode = "w";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

//p_arr($_POST);exit;

$query = $_POST['query'];

$sql_common = "
	ex_title = '".$_POST['ex_title']."',
	ex_use = '".$_POST['ex_use']."'
";


//이미지체크
$field_arr = array("ex_img_w1", "ex_img_w2", "ex_img_m1", "ex_img_m2");
foreach($field_arr as $k => $v){
	if($_FILES[$v]['tmp_name']){
		$timg = @getimagesize($_FILES[$v]['tmp_name']);
		if($timg[2] != 1 && $timg[2] != 2 && $timg[2] != 3) alert("jpg, gif, png 파일만 업로드 가능합니다.");
	}
}

if($_POST['mode'] == "insert"){

	$sql = "insert into rb_exhibition set
				$sql_common
			";
	$sql_q = sql_query($sql);
	$ex_idx = mysql_insert_id();

	//파일저장
	foreach($field_arr as $k => $v){
		if($_FILES[$v]['tmp_name']){
			$src = $_FILES[$v]['tmp_name'];
			$ext = get_file_ext($_FILES[$v]['name']);
			$tgt_name = md5(uniqid(rand(), TRUE)).".".$ext;
			$org_name = $_FILES[$v]['name'];
			$tgt = $_cfg['web_home'].$_cfg['data_dir']."/files/".$tgt_name;

			Chk_exif_WH($src, $tgt);

			sql_query("update rb_exhibition set $v = '$tgt_name', {$v}_org = '$org_name' where ex_idx = '$ex_idx'");

		}
	}

	make_ranking_write('rb_exhibition', 'ex_sort', 'ex_sort asc, ex_idx asc');


	alert("기획전이 추가되었습니다.", "./exhibition_list.php?$query");
}else if($_POST['mode'] == "update"){

	$data = sql_fetch("select * from rb_exhibition as q where q.ex_idx = '$ex_idx' $search_query");

	if(!$data['ex_idx']) alert("없는 기획전입니다.");


	$sql = "update rb_exhibition set
				$sql_common
				where ex_idx = '".$_POST['ex_idx']."'
			";
	$sql_q = sql_query($sql);
	$ex_idx = $_POST['ex_idx'];

	//파일삭제
	foreach($field_arr as $k => $v){
		if($_FILES[$v]['tmp_name']){
			sql_query("update rb_exhibition set $v = '', {$v}_org = '' where ex_idx = '$ex_idx'");
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

			sql_query("update rb_exhibition set $v = '$tgt_name', {$v}_org = '$org_name' where ex_idx = '$ex_idx'");

		}
	}

	//exit;


	alert("기획전이 수정되었습니다.", "./exhibition_view.php?ex_idx=".$_POST['ex_idx']."&$query");
}else if($_GET['mode'] == "delete"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_exhibition as q where q.ex_idx = '$ex_idx' $search_query");

	if(!$data['ex_idx']) alert("없는 기획전입니다.");

	sql_query("delete from  rb_exhibition  where ex_idx = '$ex_idx'");

	//파일삭제
	foreach($field_arr as $k => $v){
		@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/".$data[$v]);
	}

	make_ranking_write('rb_exhibition', 'ex_sort', 'ex_sort asc, ex_idx asc');

	alert("기획전이 삭제되었습니다.", "./exhibition_list.php?$query");

}else if($_POST['mode'] == "issue"){

	$data = sql_fetch("select * from rb_exhibition as q where q.ex_idx = '$ex_idx' $search_query");

	if(!$data['ex_idx']) alert("없는 기획전입니다.");

	$exhibition_group = $_POST['exhibition_group'];
	$exhibition_group_arr = ($exhibition_group != "") ? explode('|||', $exhibition_group) : array();

	for($i=0;$i<custom_count($exhibition_group_arr);$i++){
		$p_info = ($exhibition_group_arr[$i] != "") ? explode('|:|', $exhibition_group_arr[$i]) : array();
		$chk = sql_fetch("select* from rb_exhibition_product where pd_idx = '".$p_info[0]."' and ex_idx = '".$ex_idx."' ");
		if(!$chk['ep_idx']){
			sql_query("insert into rb_exhibition_product set ex_idx = '$ex_idx', pd_idx = '".$p_info[0]."' ");
		}
	}

	make_ranking_write('rb_exhibition_product', 'ep_sort', 'ep_sort asc, ep_idx asc', "ex_idx = '".$data['ex_idx']."'");

	//상품수 업데이트
	$ex_ep_cnt = sql_total("select * from rb_exhibition_product where ex_idx = '$ex_idx'");
	sql_query("update rb_exhibition set ex_ep_cnt = '$ex_ep_cnt' where ex_idx = '$ex_idx'");

	alert("기획전 상품이 추가되었습니다.", "./exhibition_issue.php?ex_idx={$ex_idx}&$query");

}else if($_GET['mode'] == "ep_delete"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "ex_idx=".$_GET['ex_idx'];
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_exhibition_product as q where q.ep_idx = '$ep_idx' $search_query");

	if(!$data['ep_idx']) alert("없는 기획전입니다.");

	sql_query("delete from  rb_exhibition_product  where ep_idx = '$ep_idx'");

	make_ranking_write('rb_exhibition_product', 'ep_sort', 'ep_sort asc, ep_idx asc', "ex_idx = '".$data['ex_idx']."'");

	//상품수 업데이트
	$ex_ep_cnt = sql_total("select * from rb_exhibition_product where ex_idx = '$ex_idx'");
	sql_query("update rb_exhibition set ex_ep_cnt = '$ex_ep_cnt' where ex_idx = '$ex_idx'");

	alert("기획전 상품이 삭제되었습니다.", "./exhibition_issue.php?ex_idx={$ex_idx}&$query");

}else if($_GET['mode'] == "sort1"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_exhibition as mn where mn.ex_idx = '$ex_idx' $search_query");

	if(!$data['ex_idx']) alert("없는 기획전입니다.");

	sql_query("update rb_exhibition set ex_sort = ex_sort + 1 where 1");

	sql_query("update rb_exhibition set ex_sort = 1 where ex_idx = '$ex_idx'");

	make_ranking_write('rb_exhibition', 'ex_sort', 'ex_sort asc, ex_idx asc');

	alert("순서가 변경되었습니다.", "./exhibition_list.php?$query");

}else if($_GET['mode'] == "sort2"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_exhibition as mn where mn.ex_idx = '$ex_idx' $search_query");

	if(!$data['ex_idx']) alert("없는 기획전입니다.");

	sql_query("update rb_exhibition set ex_sort = ex_sort + 1 where ex_sort = '".($data['ex_sort'] - 1)."' ");

	sql_query("update rb_exhibition set ex_sort = ex_sort - 1 where ex_idx = '$ex_idx'");

	make_ranking_write('rb_exhibition', 'ex_sort', 'ex_sort asc, ex_idx asc');

	alert("순서가 변경되었습니다.", "./exhibition_list.php?$query");

}else if($_GET['mode'] == "sort3"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_exhibition as mn where mn.ex_idx = '$ex_idx' $search_query");

	if(!$data['ex_idx']) alert("없는 기획전입니다.");

	sql_query("update rb_exhibition set ex_sort = ex_sort - 1 where ex_sort = '".($data['ex_sort'] + 1)."' ");

	sql_query("update rb_exhibition set ex_sort = ex_sort + 1 where ex_idx = '$ex_idx'");

	make_ranking_write('rb_exhibition', 'ex_sort', 'ex_sort asc, ex_idx asc');

	alert("순서가 변경되었습니다.", "./exhibition_list.php?$query");
}else if($_GET['mode'] == "sort4"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_exhibition as mn where mn.ex_idx = '$ex_idx' $search_query");

	if(!$data['ex_idx']) alert("없는 기획전입니다.");

	sql_query("update rb_exhibition set ex_sort = 99999999 where ex_idx = '$ex_idx'");

	make_ranking_write('rb_exhibition', 'ex_sort', 'ex_sort asc, ex_idx asc');

	alert("순서가 변경되었습니다.", "./exhibition_list.php?$query");

}else if($_GET['mode'] == "sort_1"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_exhibition_product as mn where mn.ep_idx = '$ep_idx' ");

	if(!$data['ep_idx']) alert("없는 기획전 상품입니다.");

	sql_query("update rb_exhibition_product set ep_sort = ep_sort + 1 where ex_idx = '".$data['ex_idx']."'");

	sql_query("update rb_exhibition_product set ep_sort = 1 where ep_idx = '$ep_idx'");

	make_ranking_write('rb_exhibition_product', 'ep_sort', 'ep_sort asc, ep_idx asc', "ex_idx = '".$data['ex_idx']."'");

	alert("순서가 변경되었습니다.", "./exhibition_issue.php?ex_idx={$ex_idx}&$query");

}else if($_GET['mode'] == "sort_2"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_exhibition_product as mn where mn.ep_idx = '$ep_idx' ");

	if(!$data['ep_idx']) alert("없는 기획전 상품입니다.");


	sql_query("update rb_exhibition_product set ep_sort = ep_sort + 1 where ep_sort = '".($data['ep_sort'] - 1)."' and ex_idx = '".$data['ex_idx']."'");

	sql_query("update rb_exhibition_product set ep_sort = ep_sort - 1 where ep_idx = '$ep_idx'");

	make_ranking_write('rb_exhibition_product', 'ep_sort', 'ep_sort asc, ep_idx asc', "ex_idx = '".$data['ex_idx']."'");

	alert("순서가 변경되었습니다.", "./exhibition_issue.php?ex_idx={$ex_idx}&$query");

}else if($_GET['mode'] == "sort_3"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_exhibition_product as mn where mn.ep_idx = '$ep_idx' ");

	if(!$data['ep_idx']) alert("없는 기획전 상품입니다.");

	sql_query("update rb_exhibition_product set ep_sort = ep_sort - 1 where ep_sort = '".($data['ep_sort'] + 1)."' and ex_idx = '".$data['ex_idx']."' ");

	sql_query("update rb_exhibition_product set ep_sort = ep_sort + 1 where ep_idx = '$ep_idx'");

	make_ranking_write('rb_exhibition_product', 'ep_sort', 'ep_sort asc, ep_idx asc', "ex_idx = '".$data['ex_idx']."'");

	alert("순서가 변경되었습니다.", "./exhibition_issue.php?ex_idx={$ex_idx}&$query");
}else if($_GET['mode'] == "sort_4"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_exhibition_product as mn where mn.ep_idx = '$ep_idx' ");

	if(!$data['ep_idx']) alert("없는 기획전 상품입니다.");


	sql_query("update rb_exhibition_product set ep_sort = 99999999 where ep_idx = '$ep_idx'");

	make_ranking_write('rb_exhibition_product', 'ep_sort', 'ep_sort asc, ep_idx asc', "ex_idx = '".$data['ex_idx']."'");

	alert("순서가 변경되었습니다.", "./exhibition_issue.php?ex_idx={$ex_idx}&$query");

}

alert("잘못된 접근입니다.", "./exhibition_list.php?$query");
?>