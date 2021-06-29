<?php
$menu_mode = "w";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

if(!in_array($bc_code, $_cfg['board']['bc_code'])){
	alert("없는 게시판입니다.");
}

$board_config = $_cfg['board_config'][$bc_code];

$query = $_POST['query'];


if($board_config['is_secret'] && $_POST['bd_is_secret'] != 1){
	$_POST['bd_pass'] = "";
}

$sql_common = "
	bc_code = '".$_POST['bc_code']."',
	bd_category = '".$_POST['bd_category']."',
	bd_link1 = '".$_POST['bd_link1']."',
	bd_link2 = '".$_POST['bd_link2']."',
	bd_title = '".$_POST['bd_title']."',
	bd_s_date = '".$_POST['bd_s_date']."',
	bd_e_date = '".$_POST['bd_e_date']."',
	bd_contents = '".$_POST['bd_contents']."',
	bd_answer = '".$_POST['bd_answer']."',
	bd_is_notice = '".$_POST['bd_is_notice']."',
	bd_is_secret = '".$_POST['bd_is_secret']."',
	bd_pass = '".$_POST['bd_pass']."'

";


// 파일검사
if($board_config['is_img'] == 1){
	for($i=0;$i<$_POST['file_cnt'];$i++){
		if($_FILES["bd_file_" . $i]['tmp_name']){
			if($board_config['is_img'] == 1){
				$timg = @getimagesize($_FILES["bd_file_" . $i]['tmp_name']);
				if($timg[2] != 1 && $timg[2] != 2 && $timg[2] != 3) alert("jpg, gif, png 파일만 업로드 가능합니다.");
			}
		}
	}
}
//p_arr($_POST);exit;

if($_POST['mode'] == "insert"){

	$bd_num  = get_bd_num($bc_code);

	$sql = "insert into rb_board set
				$sql_common,
				bd_num = '".$bd_num."',
				mb_id = '".$member['mb_id']."',
				bd_regdate = now()
			";
	$sql_q = sql_query($sql);
	$bd_idx = mysql_insert_id();

	//파일저장

	for($i=0;$i<$_POST['file_cnt'];$i++){
		if($_FILES["bd_file_" . $i]['tmp_name']){
			$src = $_FILES["bd_file_" . $i]['tmp_name'];
			$ext = strtolower(get_file_ext($_FILES["bd_file_" . $i]['name']));
			$tgt_name = md5(uniqid(rand(), TRUE)).".".$ext;
			$org_name = $_FILES["bd_file_" . $i]['name'];
			$tgt = $_cfg['web_home'].$_cfg['data_dir']."/files/".$tgt_name;
			$fi_size = filesize($src);

			Chk_exif_WH($src, $tgt);
			if($board_config['is_img'] == 1){
				$thumb = $_cfg['web_home'].$_cfg['data_dir']."/files/thumb_".$tgt_name;
				put_gdimage($tgt, 100, 0, $thumb);
			}

			sql_query("insert into rb_board_file set fi_num = '$i', bd_idx = '$bd_idx', bc_code = '$bc_code', fi_name = '$tgt_name', fi_name_org = '$org_name', fi_size = '$fi_size', fi_regdate = now() ");

		}
	}

	//파일카운트
	$bd_file_cnt = sql_total("select * from rb_board_file where bd_idx = '$bd_idx'");
	sql_query("update rb_board set bd_file_cnt = '$bd_file_cnt' where bd_idx = '$bd_idx'");

	alert("게시글이 작성되었습니다.", "./board_list.php?$query");
}else if($_POST['mode'] == "reply"){


	$data_parent = sql_fetch("select * from rb_board as b where b.bc_code = '$bc_code' and b.bd_idx = '$bd_parent' ");

	if(!$data_parent['bd_idx']) alert("원글이 없는 게시글입니다.");
	if($data_parent['bd_depth'] > 0) alert("답글엔 답글을 달수 없습니다.");

	$bd_num = $data_parent['bd_num'];
	$bd_depth = $data_parent['bd_depth'] + 1;
	push_bd_num($bc_code, $bd_num);


	$sql = "insert into rb_board set
				$sql_common,
				bd_num = '".$bd_num."',
				bd_depth = '".$bd_depth."',
				bd_parent = '".$bd_parent."',
				mb_id = '".$member['mb_id']."',
				bd_regdate = now()
			";
	$sql_q = sql_query($sql);
	$bd_idx = mysql_insert_id();

	//파일저장

	for($i=0;$i<$_POST['file_cnt'];$i++){
		if($_FILES["bd_file_" . $i]['tmp_name']){
			$src = $_FILES["bd_file_" . $i]['tmp_name'];
			$ext = strtolower(get_file_ext($_FILES["bd_file_" . $i]['name']));
			$tgt_name = md5(uniqid(rand(), TRUE)).".".$ext;
			$org_name = $_FILES["bd_file_" . $i]['name'];
			$tgt = $_cfg['web_home'].$_cfg['data_dir']."/files/".$tgt_name;
			$fi_size = filesize($src);

			Chk_exif_WH($src, $tgt);
			if($board_config['is_img'] == 1){
				$thumb = $_cfg['web_home'].$_cfg['data_dir']."/files/thumb_".$tgt_name;
				put_gdimage($tgt, 100, 0, $thumb);
			}

			sql_query("insert into rb_board_file set fi_num = '$i', bd_idx = '$bd_idx', bc_code = '$bc_code', fi_name = '$tgt_name', fi_name_org = '$org_name', fi_size = '$fi_size', fi_regdate = now() ");

		}
	}


	//파일카운트
	$bd_file_cnt = sql_total("select * from rb_board_file where bd_idx = '$bd_idx'");
	sql_query("update rb_board set bd_file_cnt = '$bd_file_cnt' where bd_idx = '$bd_idx'");


	alert("게시글이 작성되었습니다.", "./board_view.php?bd_idx={$bd_idx}&$query");
}else if($_POST['mode'] == "update"){

	$data = sql_fetch("select * from rb_board as b left join rb_member as m on b.mb_id = m.mb_id where b.bc_code = '$bc_code' and b.bd_idx = '$bd_idx' $search_query");
	if(!$data['bd_idx']) alert("없는 게시글입니다.");


	//파일삭제
	for($i=0;$i<$_POST['file_cnt'];$i++){
		if($_POST["bd_file_del_".$i] == 1 && $_POST["fi_idx_".$i]){
			$f_data = sql_fetch("select * from rb_board_file where fi_idx = '".$_POST["fi_idx_".$i]."'");
			sql_query("delete from rb_board_file where fi_idx = '".$_POST["fi_idx_".$i]."'");
			@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/".$f_data['fi_name']);
			@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/thumb_".$f_data['fi_name']);
		}
	}

	$sql = "update rb_board set
				$sql_common
				where bd_idx = '".$_POST['bd_idx']."'
			";
	$sql_q = sql_query($sql);
	$bd_idx = $_POST['bd_idx'];

	//파일저장
	$fi_num = 0;
	for($i=0;$i<$_POST['file_cnt'];$i++){
		if($_FILES["bd_file_" . $i]['tmp_name']){

			if($_POST["fi_idx_".$i]){
				$f_data = sql_fetch("select * from rb_board_file where fi_idx = '".$_POST["fi_idx_".$i]."'");
				sql_query("delete from rb_board_file where fi_idx = '".$_POST["fi_idx_".$i]."'");
				@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/".$f_data['fi_name']);
				@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/thumb_".$f_data['fi_name']);
			}

			$src = $_FILES["bd_file_" . $i]['tmp_name'];
			$ext = strtolower(get_file_ext($_FILES["bd_file_" . $i]['name']));
			$tgt_name = md5(uniqid(rand(), TRUE)).".".$ext;
			$org_name = $_FILES["bd_file_" . $i]['name'];
			$tgt = $_cfg['web_home'].$_cfg['data_dir']."/files/".$tgt_name;
			$fi_size = filesize($src);

			Chk_exif_WH($src, $tgt);
			if($board_config['is_img'] == 1){
				$thumb = $_cfg['web_home'].$_cfg['data_dir']."/files/thumb_".$tgt_name;
				put_gdimage($tgt, 100, 0, $thumb);
			}

			sql_query("insert into rb_board_file set fi_num = '$fi_num', bd_idx = '$bd_idx', bc_code = '$bc_code', fi_name = '$tgt_name', fi_name_org = '$org_name', fi_size = '$fi_size', fi_regdate = now() ");
			$fi_num++;
		}else{
			if($_POST["fi_idx_".$i]){
				$f_data = sql_fetch("select * from rb_board_file where fi_idx = '".$_POST["fi_idx_".$i]."'");
				if($f_data['fi_idx']){
					sql_query("update rb_board_file set fi_num = '$fi_num' where fi_idx = '".$_POST["fi_idx_".$i]."'");
					$fi_num++;
				}
			}
		}
	}

	//파일카운트
	$bd_file_cnt = sql_total("select * from rb_board_file where bd_idx = '$bd_idx'");
	sql_query("update rb_board set bd_file_cnt = '$bd_file_cnt' where bd_idx = '$bd_idx'");

	alert("게시글이 수정되었습니다.", "./board_view.php?bd_idx=".$_POST['bd_idx']."&$query");
}else if($_GET['mode'] == "delete"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	$querys[] = "bc_code=".$bc_code;
	if(is_array($board_config['category'])){
		if($_GET['bd_category']){
			$querys[] = "bd_category=".$_GET['bd_category'];
		}
	}
	
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	//echo "select * from rb_board as b left join rb_member as m on b.mb_id = m.mb_id where b.bc_code = '$bc_code' and b.bd_idx = '$bd_idx' $search_query";exit;

	$data = sql_fetch("select * from rb_board as b left join rb_member as m on b.mb_id = m.mb_id where b.bc_code = '$bc_code' and b.bd_idx = '$bd_idx' $search_query");
	if(!$data['bd_idx']) alert("없는 게시글입니다.");


	delete_board_article($bd_idx);

	alert("게시글이 삭제되었습니다.", "./board_list.php?$query");

}else if($_GET['mode'] == "c_delete"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	$querys[] = "bc_code=".$bc_code;
	if(is_array($board_config['category'])){
		if($_GET['bd_category']){
			$querys[] = "bd_category=".$_GET['bd_category'];
		}
	}
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_board as b left join rb_member as m on b.mb_id = m.mb_id where b.bc_code = '$bc_code' and b.bd_idx = '$bd_idx' $search_query");
	if(!$data['bd_idx']) alert("없는 게시글입니다.");

	if(!$board_config['is_comment']) alert("잘못된 접근입니다.");

	$data2 = sql_fetch("select * from rb_board_comment where bd_idx = '$bd_idx' and cm_idx = '$cm_idx'");
	if(!$data2['cm_idx']) alert("없는 댓글입니다.");


	$sql = "delete from rb_board_comment where cm_idx = '$cm_idx'
			";
	$sql_q = sql_query($sql);

	alert("댓글이 삭제되었습니다.", "./board_view.php?bd_idx=".$_POST['bd_idx']."&$query");

}

alert("잘못된 접근입니다.", "./board_list.php?$query");
?>