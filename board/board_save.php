<?php
include "../inc/_common.php";
include "../inc/_head.php";

include "../_inc/_board_config.php";


$query = $_POST['query'];

if($_POST['mode'] == "insert" || $_POST['mode'] == "reply"){
	if($is_member){
		$_POST['bd_name'] = $member['mb_name'];
	}else{
		if($_POST['bd_name'] == ""){alert("이름을 입력하세요.");}
	}
}

if($board_config['is_secret'] && $_POST['bd_is_secret'] != 1){
	$_POST['bd_pass'] = "";
}


$sql_common = "
	bc_code = '".$_POST['bc_code']."',
	bd_category = '".$_POST['bd_category']."',
	bd_name = '".$_POST['bd_name']."',
	bd_link1 = '".$_POST['bd_link1']."',
	bd_link2 = '".$_POST['bd_link2']."',
	bd_title = '".$_POST['bd_title']."',
	bd_s_date = '".$_POST['bd_s_date']."',
	bd_e_date = '".$_POST['bd_e_date']."',
	bd_contents = '".$_POST['bd_contents']."',
	bd_is_secret = '".$_POST['bd_is_secret']."',
	bd_pass = '".$_POST['bd_pass']."'

";


// 파일검사
if($board_config['is_img'] == 1){
	for($i=0;$i<$_POST['file_cnt'];$i++){
		if($user_agent == "app"){
			if($_POST["bd_file_" . $i]){
				$timg = @getimagesize($_cfg['web_home']."/data/tmp/".$_POST["bd_file_" . $i]);
				if($timg[2] != 1 && $timg[2] != 2 && $timg[2] != 3) alert($_lang['board']['text_0665']);
			}
		}else{
			if($_FILES["bd_file_" . $i]['tmp_name']){
				$timg = @getimagesize($_FILES["bd_file_" . $i]['tmp_name']);
				if($timg[2] != 1 && $timg[2] != 2 && $timg[2] != 3) alert($_lang['board']['text_0665']);
			}
		}
	}
}

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
	// for($i=0;$i<$_POST[file_cnt];$i++){
	// 	if($user_agent == "app"){
	// 		if($_POST["bd_file_" . $i]){
	// 			$src = $_cfg['web_home']."/data/tmp/".$_POST["bd_file_" . $i];
	// 			$ext = strtolower(get_file_ext($_POST["bd_file_" . $i]));
	// 			$tgt_name = md5(uniqid(rand(), TRUE)).".".$ext;
	// 			$tgt_name = preg_replace("/\.(php|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $tgt_name);
	// 			$org_name = $_POST["bd_file_" . $i."_org"];
	// 			$tgt = $_cfg['web_home'].$_cfg['data_dir']."/files/".$tgt_name;
	// 			$fi_size = filesize($src);

	// 			Chk_exif_WH2($src, $tgt);
	// 			if($board_config['is_img'] == 1){
	// 				$thumb = $_cfg['web_home'].$_cfg['data_dir']."/files/thumb_".$tgt_name;
	// 				put_gdimage($tgt, 100, 0, $thumb);
	// 			}

	// 			sql_query("insert into rb_board_file set fi_num = '$i', bd_idx = '$bd_idx', bc_code = '$bc_code', fi_name = '$tgt_name', fi_name_org = '$org_name', fi_size = '$fi_size', fi_regdate = now() ");

	// 		}
	// 	}else{
	// 		if($_FILES["bd_file_" . $i]['tmp_name']){
	// 			$src = $_FILES["bd_file_" . $i]['tmp_name'];
	// 			$ext = strtolower(get_file_ext($_FILES["bd_file_" . $i]['name']));
	// 			$tgt_name = md5(uniqid(rand(), TRUE)).".".$ext;
	// 			$tgt_name = preg_replace("/\.(php|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $tgt_name);
	// 			$org_name = $_FILES["bd_file_" . $i]['name'];
	// 			$tgt = $_cfg['web_home'].$_cfg['data_dir']."/files/".$tgt_name;
	// 			$fi_size = filesize($src);


	// 			Chk_exif_WH($src, $tgt);
	// 			if($board_config['is_img'] == 1){
	// 				$thumb = $_cfg['web_home'].$_cfg['data_dir']."/files/thumb_".$tgt_name;
	// 				put_gdimage($tgt, 100, 0, $thumb);
	// 			}

	// 			sql_query("insert into rb_board_file set fi_num = '$i', bd_idx = '$bd_idx', bc_code = '$bc_code', fi_name = '$tgt_name', fi_name_org = '$org_name', fi_size = '$fi_size', fi_regdate = now() ");

	// 		}
	// 	}
	// }

	//파일저장 => 멀티업로드 버전
	$num = 0;
	for($i=0;$i<$_POST['file_cnt_total'];$i++){
		
			if($_POST["bd_file_" . $i]){
				$src = $_cfg['web_home']."/data/tmp/".$_POST["bd_file_" . $i];
				$ext = strtolower(get_file_ext($_POST["bd_file_" . $i]));
				$tgt_name = md5(uniqid(rand(), TRUE)).".".$ext;
				$tgt_name = preg_replace("/\.(php|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $tgt_name);
				$org_name = $_POST["bd_file_" . $i."_org"];
				$tgt = $_cfg['web_home'].$_cfg['data_dir']."/files/".$tgt_name;
				$fi_size = filesize($src);

				Chk_exif_WH2($src, $tgt);

				if($board_config['is_img'] == 1){
					$thumb = $_cfg['web_home'].$_cfg['data_dir']."/files/thumb_".$tgt_name;
					put_gdimage($tgt, 240, 0, $thumb);
				}

				sql_query("insert into rb_board_file set fi_num = '$num', bd_idx = '$bd_idx', bc_code = '$bc_code', fi_name = '$tgt_name', fi_name_org = '$org_name', fi_size = '$fi_size', fi_regdate = now() ");
				$num++;

			}
		
	}

	//파일카운트
	$bd_file_cnt = sql_total("select * from rb_board_file where bd_idx = '$bd_idx'");
	sql_query("update rb_board set bd_file_cnt = '$bd_file_cnt' where bd_idx = '$bd_idx'");

	alert($_lang['board']['text_0666'], "/board/board_list.php?$query");
}else if($_POST['mode'] == "reply"){


	$data_parent = sql_fetch("select * from rb_board as b where b.bc_code = '$bc_code' and b.bd_idx = '$bd_parent' ");

	if(!$data_parent['bd_idx']) alert("원글이 없는 게시글입니다.");
	if($data_parent['bd_depth'] > 0) alert("답글엔 답글을 달수 없습니다.");


	if($board_config['is_secret'] && $data_parent['bd_pass']){
		if($data_parent['bd_idx'] != $_SESSION['board_view']){
			alert("원글이 비밀글입니다..");
		}
	}

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
		if($user_agent == "app"){
			if($_POST["bd_file_" . $i]){
				$src = $_cfg['web_home']."/data/tmp/".$_POST["bd_file_" . $i];
				$ext = strtolower(get_file_ext($_POST["bd_file_" . $i]));
				$tgt_name = md5(uniqid(rand(), TRUE)).".".$ext;
				$tgt_name = preg_replace("/\.(php|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $tgt_name);
				$org_name = $_POST["bd_file_" . $i."_org"];
				$tgt = $_cfg['web_home'].$_cfg['data_dir']."/files/".$tgt_name;
				$fi_size = filesize($src);

				Chk_exif_WH2($src, $tgt);
				if($board_config['is_img'] == 1){
					$thumb = $_cfg['web_home'].$_cfg['data_dir']."/files/thumb_".$tgt_name;
					put_gdimage($tgt, 100, 0, $thumb);
				}

				sql_query("insert into rb_board_file set fi_num = '$i', bd_idx = '$bd_idx', bc_code = '$bc_code', fi_name = '$tgt_name', fi_name_org = '$org_name', fi_size = '$fi_size', fi_regdate = now() ");

			}
		}else{
			if($_FILES["bd_file_" . $i]['tmp_name']){
				$src = $_FILES["bd_file_" . $i]['tmp_name'];
				$ext = strtolower(get_file_ext($_FILES["bd_file_" . $i]['name']));
				$tgt_name = md5(uniqid(rand(), TRUE)).".".$ext;
				$tgt_name = preg_replace("/\.(php|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $tgt_name);
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
	}


	//파일카운트
	$bd_file_cnt = sql_total("select * from rb_board_file where bd_idx = '$bd_idx'");
	sql_query("update rb_board set bd_file_cnt = '$bd_file_cnt' where bd_idx = '$bd_idx'");

	alert("게시글이 작성되었습니다.", "/board/board_view.php?bd_idx={$bd_idx}$query");
}else if($_POST['mode'] == "update"){

	$data = sql_fetch("select * from rb_board as b left join rb_member as m on b.mb_id = m.mb_id where b.bc_code = '$bc_code' and b.bd_idx = '$bd_idx' $search_query");
	if(!$data['bd_idx']) alert($_lang['board']['text_0659']);

	if(is_secret_article($board_config, $data['bd_idx'], $data['bd_is_secret'], $data['mb_id'])){
		if($data['bd_idx'] != $_SESSION['board_view'] && !$is_admin){
			alert($_lang['board']['text_0660']);
		}
	}

	if($data['mb_id'] != $member['mb_id'] && !$is_super){
		alert($_lang['board']['text_0661']);
	}

	//파일삭제
	// for($i=0;$i<$_POST['file_cnt'];$i++){
	// 	if($_POST["bd_file_del_".$i] == 1 && $_POST["fi_idx_".$i]){
	// 		$f_data = sql_fetch("select * from rb_board_file where fi_idx = '".$_POST["fi_idx_".$i]."'");
	// 		sql_query("delete from rb_board_file where fi_idx = '".$_POST["fi_idx_".$i]."'");
	// 		@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/".$f_data['fi_name']);
	// 		@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/thumb_".$f_data['fi_name']);
	// 	}
	// }

	//파일삭제 => 멀티업로드
	for($i=0;$i<$_POST['file_cnt_total'];$i++){
		if($_POST["bd_file_".$i."_del"] == 1 && $_POST["fi_idx_".$i]){
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
	// $fi_num = 0;
	// for($i=0;$i<$_POST['file_cnt'];$i++){
	// 	if($user_agent == "app"){


	// 		if($_POST["bd_file_" . $i]){

	// 			if($_POST["fi_idx_".$i]){
	// 				$f_data = sql_fetch("select * from rb_board_file where fi_idx = '".$_POST["fi_idx_".$i]."'");
	// 				sql_query("delete from rb_board_file where fi_idx = '".$_POST["fi_idx_".$i]."'");
	// 				@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/".$f_data['fi_name']);
	// 				@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/thumb_".$f_data['fi_name']);
	// 			}

	// 			$src = $_cfg['web_home']."/data/tmp/".$_POST["bd_file_" . $i];
	// 			$ext = strtolower(get_file_ext($_POST["bd_file_" . $i]));
	// 			$tgt_name = md5(uniqid(rand(), TRUE)).".".$ext;
	// 			$tgt_name = preg_replace("/\.(php|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $tgt_name);
	// 			$org_name = $_POST["bd_file_" . $i."_org"];
	// 			$tgt = $_cfg['web_home'].$_cfg['data_dir']."/files/".$tgt_name;
	// 			$fi_size = filesize($src);

	// 			Chk_exif_WH2($src, $tgt);
	// 			if($board_config['is_img'] == 1){
	// 				$thumb = $_cfg['web_home'].$_cfg['data_dir']."/files/thumb_".$tgt_name;
	// 				put_gdimage($tgt, 100, 0, $thumb);
	// 			}

	// 			sql_query("insert into rb_board_file set fi_num = '$fi_num', bd_idx = '$bd_idx', bc_code = '$bc_code', fi_name = '$tgt_name', fi_name_org = '$org_name', fi_size = '$fi_size', fi_regdate = now() ");
	// 			$fi_num++;
	// 		}else{
	// 			if($_POST["fi_idx_".$i]){
	// 				$f_data = sql_fetch("select * from rb_board_file where fi_idx = '".$_POST["fi_idx_".$i]."'");
	// 				if($f_data['fi_idx']){
	// 					sql_query("update rb_board_file set fi_num = '$fi_num' where fi_idx = '".$_POST["fi_idx_".$i]."'");
	// 					$fi_num++;
	// 				}
	// 			}
	// 		}

	// 	}else{
	// 		if($_FILES["bd_file_" . $i]['tmp_name']){

	// 			if($_POST["fi_idx_".$i]){
	// 				$f_data = sql_fetch("select * from rb_board_file where fi_idx = '".$_POST["fi_idx_".$i]."'");
	// 				sql_query("delete from rb_board_file where fi_idx = '".$_POST["fi_idx_".$i]."'");
	// 				@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/".$f_data['fi_name']);
	// 				@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/thumb_".$f_data['fi_name']);
	// 			}

	// 			$src = $_FILES["bd_file_" . $i]['tmp_name'];
	// 			$ext = strtolower(get_file_ext($_FILES["bd_file_" . $i]['name']));
	// 			$tgt_name = md5(uniqid(rand(), TRUE)).".".$ext;
	// 			$org_name = $_FILES["bd_file_" . $i]['name'];
	// 			$tgt = $_cfg['web_home'].$_cfg['data_dir']."/files/".$tgt_name;
	// 			$fi_size = filesize($src);


	// 			Chk_exif_WH($src, $tgt);
	// 			if($board_config['is_img'] == 1){
	// 				$thumb = $_cfg['web_home'].$_cfg['data_dir']."/files/thumb_".$tgt_name;
	// 				put_gdimage($tgt, 100, 0, $thumb);
	// 			}

	// 			sql_query("insert into rb_board_file set fi_num = '$fi_num', bd_idx = '$bd_idx', bc_code = '$bc_code', fi_name = '$tgt_name', fi_name_org = '$org_name', fi_size = '$fi_size', fi_regdate = now() ");
	// 			$fi_num++;
	// 		}else{
	// 			if($_POST["fi_idx_".$i]){
	// 				$f_data = sql_fetch("select * from rb_board_file where fi_idx = '".$_POST["fi_idx_".$i]."'");
	// 				if($f_data[fi_idx]){
	// 					sql_query("update rb_board_file set fi_num = '$fi_num' where fi_idx = '".$_POST["fi_idx_".$i]."'");
	// 					$fi_num++;
	// 				}
	// 			}
	// 		}
	// 	}
	// }

	//파일저장 => 멀티업로드 버전
	$fi_num = 0;
	for($i=0;$i<$_POST['file_cnt_total'];$i++){

			if($_POST["bd_file_" . $i]){

				if($_POST["fi_idx_".$i]){
					$f_data = sql_fetch("select * from rb_board_file where fi_idx = '".$_POST["fi_idx_".$i]."'");
					sql_query("delete from rb_board_file where fi_idx = '".$_POST["fi_idx_".$i]."'");
					@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/".$f_data['fi_name']);
					@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/thumb_".$f_data['fi_name']);
				}

				$src = $_cfg['web_home']."/data/tmp/".$_POST["bd_file_" . $i];
				$ext = strtolower(get_file_ext($_POST["bd_file_" . $i]));
				$tgt_name = md5(uniqid(rand(), TRUE)).".".$ext;
				$tgt_name = preg_replace("/\.(php|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $tgt_name);
				$org_name = $_POST["bd_file_" . $i."_org"];
				$tgt = $_cfg['web_home'].$_cfg['data_dir']."/files/".$tgt_name;
				$fi_size = filesize($src);

				Chk_exif_WH2($src, $tgt);
				$thumb = $_cfg['web_home'].$_cfg['data_dir']."/files/thumb_".$tgt_name;
				put_gdimage($tgt, 240, 0, $thumb);


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


	alert($_lang['board']['text_0667'], "/board/board_view.php?bd_idx=".$_POST['bd_idx']."&$query");
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

	$data = sql_fetch("select * from rb_board as b left join rb_member as m on b.mb_id = m.mb_id where b.bc_code = '$bc_code' and b.bd_idx = '$bd_idx' $search_query");
	if(!$data['bd_idx']) alert($_lang['board']['text_0659']);

	if(is_secret_article($board_config, $data['bd_idx'], $data['bd_is_secret'], $data['mb_id'])){
		if($data['bd_idx'] != $_SESSION['board_view'] && !$is_admin){
			alert($_lang['board']['text_0660']);
		}
	}


	if($data['mb_id'] != $member['mb_id'] && !$is_super){
		alert($_lang['board']['text_0661']);
	}

	delete_board_article($bd_idx);


	alert($_lang['board']['text_0668'], "/board/board_list.php?$query");

}else if($_POST['mode'] == "c_insert"){

	$data = sql_fetch("select * from rb_board as b left join rb_member as m on b.mb_id = m.mb_id where b.bc_code = '$bc_code' and b.bd_idx = '$bd_idx' $search_query");
	if(!$data['bd_idx']) alert("없는 게시글입니다.");

	if(!$board_config['is_comment']) alert("잘못된 접근입니다.");

	if($board_config['comment_insert_level'] > $m_level){
		alert("권한이 없습니다.");
	}


	$sql = "insert into rb_board_comment set
				bc_code = '".$_POST['bc_code']."',
				mb_id = '".$member['mb_id']."',
				bd_idx = '".$_POST['bd_idx']."',
				cm_contents = '".$_POST['cm_contents']."',
				cm_regdate = now()
			";
	$sql_q = sql_query($sql);

	alert("댓글이 추가되었습니다.", "/board/board_view.php?bd_idx=".$_POST['bd_idx']."&$query");

}else if($_POST['mode'] == "c_update"){

	$data = sql_fetch("select * from rb_board as b left join rb_member as m on b.mb_id = m.mb_id where b.bc_code = '$bc_code' and b.bd_idx = '$bd_idx' $search_query");
	if(!$data['bd_idx']) alert("없는 게시글입니다.");

	if(!$board_config['is_comment']) alert("잘못된 접근입니다.");

	if($board_config['comment_insert_level'] > $m_level){
		alert("권한이 없습니다.");
	}



	$data2 = sql_fetch("select * from rb_board_comment where bd_idx = '$bd_idx' and cm_idx = '$cm_idx'");
	if(!$data2['cm_idx']) alert("없는 댓글입니다.");

	if($data2['mb_id'] != $member['mb_id']){
		alert("권한이 없습니다.");
	}

	$sql = "update rb_board_comment set
				cm_contents = '".$_POST['cm_contents']."'
				where cm_idx = '$cm_idx'
			";
	$sql_q = sql_query($sql);

	alert("댓글이 수정되었습니다.", "/board/board_view.php?bd_idx=".$_POST['bd_idx']."&$query");

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

	if($board_config['comment_insert_level'] > $m_level){
		alert("권한이 없습니다.");
	}

	$data2 = sql_fetch("select * from rb_board_comment where bd_idx = '$bd_idx' and cm_idx = '$cm_idx'");
	if(!$data2['cm_idx']) alert("없는 댓글입니다.");

	if($data2['mb_id'] != $member['mb_id']){
		alert("권한이 없습니다.");
	}


	$sql = "delete from rb_board_comment where cm_idx = '$cm_idx'
			";
	$sql_q = sql_query($sql);

	sql_query("delete from  rb_board_comment_like  where cm_idx = '".$cm_idx."'");

	alert("댓글이 삭제되었습니다.", "/board/board_view.php?bd_idx=".$_GET['bd_idx']."&$query");

}
alert($_lang['board']['text_0669']);
include "../inc/_tail.php";
?>