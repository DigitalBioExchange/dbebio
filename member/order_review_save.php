<?php
include "../inc/_common.php";
include "../inc/_head.php";

// p_arr($_POST);
// exit;

$query = $_POST['query'];

$sql = "select * from rb_order_cart where od_idx = '".$od_idx."' and ct_idx = '".$ct_idx."' and mb_id = '".$member['mb_id']."' ";
$data = sql_fetch($sql);

if (!$data['ct_idx']) {
	alert($_lang['inc']['text_0746']);
}


// 파일검사
for($i=0;$i<$_POST['file_cnt'];$i++){
	if($_POST["bd_file_" . $i]){
		$timg = @getimagesize($_cfg['web_home']."/data/tmp/".$_POST["bd_file_" . $i]);
		if($timg[2] != 1 && $timg[2] != 2 && $timg[2] != 3) alert($_lang['board']['text_0665']);
	}
}


if($_POST['mode'] == "insert"){

	$sql = "insert into rb_product_review set
				pr_point = '".$pr_point."',
				pr_contents = '".$pr_contents."',
				od_idx = '".$od_idx."',
				ct_idx = '".$ct_idx."',
				pd_idx = '".$data['pd_idx']."',
				mb_id = '".$member['mb_id']."',
				pr_regdate = now()
			";
	$sql_q = sql_query($sql);
	$pr_idx = mysql_insert_id();


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

				$thumb = $_cfg['web_home'].$_cfg['data_dir']."/files/thumb_".$tgt_name;
				put_gdimage($tgt, 240, 0, $thumb);
			

				sql_query("insert into rb_product_review_file set fi_num = '$num', pr_idx = '$pr_idx', fi_name = '$tgt_name', fi_name_org = '$org_name', fi_size = '$fi_size', fi_regdate = now() ");
				$num++;

			}
		
	}

	//파일카운트
	$pr_file_cnt = sql_total("select * from rb_product_review_file where pr_idx = '$pr_idx'");
	sql_query("update rb_product_review set pr_file_cnt = '$pr_file_cnt' where pr_idx = '$pr_idx'");

	alert($_lang['tpl_shop']['text_0516'], "/member/order_list.php");

} else if ($_POST['mode'] == "update"){
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

}

alert($_lang['board']['text_0669']);
include "../inc/_tail.php";
?>