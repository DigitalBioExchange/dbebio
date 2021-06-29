<?php
$menu_code = "400100";
$menu_mode = "w";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

$product_config = $_cfg['product_config'];

$query = $_POST['query'];

$sql_common = "
	c1_idx = '".$_POST['c1_idx']."',
	c2_idx = '".$_POST['c2_idx']."',
	c3_idx = '".$_POST['c3_idx']."',

	pd_price = '".$_POST['pd_price']."',
	pd_price2 = '".$_POST['pd_price2']."',
	pd_use = '".$_POST['pd_use']."',

	pd_origin = '".$_POST['pd_origin']."',

	pd_option1_name = '".$_POST['pd_option1_name']."',
	pd_option2_name = '".$_POST['pd_option2_name']."',
	pd_option1 = '".$_POST['pd_option1']."',
	pd_option2 = '".$_POST['pd_option2']."',

	pd_delivery_type = '".$_POST['pd_delivery_type']."',
	pd_delivery_type_cnt = '".$_POST['pd_delivery_type_cnt']."',
	pd_delivery_type2 = '".$_POST['pd_delivery_type2']."',
	pd_delivery_free_amount = '".$_POST['pd_delivery_free_amount']."',
	pd_delivery_amount = '".$_POST['pd_delivery_amount']."'
	

";

foreach ($_cfg['common']['lang_code'] as $key => $value) {
	$sql_common .= "
		, pd_name_".$value['val']." = '".$_POST['pd_name_'.$value['val']]."'
		, pd_exp_".$value['val']." = '".$_POST['pd_exp_'.$value['val']]."'
		, pd_contents_".$value['val']." = '".$_POST['pd_contents_'.$value['val']]."'
		, pd_delivery_contents_".$value['val']." = '".$_POST['pd_delivery_contents_'.$value['val']]."'
	";
}

// echo $sql_common;exit;
// 파일검사
for($i=0;$i<$_POST['file_cnt'];$i++){
	if($_FILES["pd_file_" . $i]['tmp_name']){
		$timg = @getimagesize($_FILES["pd_file_" . $i]['tmp_name']);
		if($timg[2] != 1 && $timg[2] != 2 && $timg[2] != 3) alert("jpg, gif, png 파일만 업로드 가능합니다.");
	}
}

//이미지체크
// $field_arr = array("pd_img");
// foreach($field_arr as $k => $v){
// 	if($_FILES[$v]['tmp_name']){
// 		$timg = @getimagesize($_FILES[$v]['tmp_name']);
// 		if($timg[2] != 1 && $timg[2] != 2 && $timg[2] != 3) alert("jpg, gif, png 파일만 업로드 가능합니다.");
// 	}
// }


if($_POST['mode'] == "insert"){

	$sql = "insert into rb_product set
				$sql_common,
				pd_regdate = now()
			";
	$sql_q = sql_query($sql);
	$pd_idx = sql_insert_id();

	//파일저장
	for($i=0;$i<$_POST['file_cnt'];$i++){
		if($_FILES["pd_file_" . $i]['tmp_name']){
			$src = $_FILES["pd_file_" . $i]['tmp_name'];
			$ext = strtolower(get_file_ext($_FILES["pd_file_" . $i]['name']));
			$tgt_name = md5(uniqid(rand(), TRUE)).".".$ext;
			$org_name = $_FILES["pd_file_" . $i]['name'];
			$tgt = $_cfg['web_home'].$_cfg['data_dir']."/files/".$tgt_name;
			$fi_size = filesize($src);

			Chk_exif_WH($src, $tgt);
			$thumb1 = $_cfg['web_home'].$_cfg['data_dir']."/files/thumb1_".$tgt_name;
			put_gdimage($tgt, $product_config['thumb1_size'], 0, $thumb1);

			$thumb2 = $_cfg['web_home'].$_cfg['data_dir']."/files/thumb2_".$tgt_name;
			put_gdimage($tgt, $product_config['thumb2_size'], 0, $thumb2);

			sql_query("insert into rb_product_file set fi_num = '$i', pd_idx = '$pd_idx', fi_name = '$tgt_name', fi_name_org = '$org_name', fi_size = '$fi_size', fi_regdate = now() ");

		}
	}

	//파일카운트
	$pd_file_cnt = sql_total("select * from rb_product_file where pd_idx = '$pd_idx'");
	sql_query("update rb_product set pd_file_cnt = '$pd_file_cnt' where pd_idx = '$pd_idx'");


	//파일저장
	// foreach($field_arr as $k => $v){
	// 	if($_FILES[$v]['tmp_name']){
	// 		$src = $_FILES[$v]['tmp_name'];
	// 		$ext = get_file_ext($_FILES[$v]['name']);
	// 		$tgt_name = md5(uniqid(rand(), TRUE)).".".$ext;
	// 		$org_name = $_FILES[$v]['name'];
	// 		$tgt = $_cfg['web_home'].$_cfg['data_dir']."/files/".$tgt_name;

	// 		Chk_exif_WH($src, $tgt);

	// 		$thumb1 = $_cfg['web_home'].$_cfg['data_dir']."/files/thumb1_".$tgt_name;
	// 		put_gdimage($tgt, $product_config['thumb1_size'], 0, $thumb1);

	// 		$thumb2 = $_cfg['web_home'].$_cfg['data_dir']."/files/thumb2_".$tgt_name;
	// 		put_gdimage($tgt, $product_config['thumb2_size'], 0, $thumb2);

	// 		sql_query("update rb_product set $v = '$tgt_name', {$v}_org = '$org_name' where pd_idx = '$pd_idx'");

	// 	}
	// }


	// ////카테고리3,4 정리
	// if(custom_count($_POST['pd_cate']) > 0){
	// 	for($i=0;$i<custom_count($_POST['pd_cate']);$i++){
	// 		$ca_idx = $_POST['pd_cate'][$i];
	// 		sql_query("insert into rb_product_cate set ca_idx = '$ca_idx', pd_idx = '$pd_idx'");
	// 	}
	// }

	// //그룹상품
	// if($_POST['pd_group'] == 1){
	// 	sql_query("delete from rb_product_group where parent_idx = '$pd_idx'");
	// }else{
	// 	$product_group = $_POST['product_group'];
	// 	$product_group_arr = ($product_group != "") ? explode('|;|', $product_group) : array();
	// 	for($i=0;$i<custom_count($product_group_arr);$i++){
	// 		$p_info = ($product_group_arr[$i] != "") ? explode('|:|', $product_group_arr[$i]) : array();
	// 		sql_query("insert into rb_product_group set parent_idx = '$pd_idx', pd_idx = '".$p_info[0]."', pg_option = '".$p_info[2]."', pg_cnt = '".$p_info[4]."', pg_order = '".($i+1)."'");
	// 	}		
	// }

	// //연관상품
	// $product_relation = $_POST['product_relation'];
	// $product_relation_arr = ($product_relation != "") ? explode('|;|', $product_relation) : array();
	// for($i=0;$i<custom_count($product_relation_arr);$i++){
	// 	$p_info = ($product_relation_arr[$i] != "") ? explode('|:|', $product_relation_arr[$i]) : array();
	// 	sql_query("insert into rb_product_relation set parent_idx = '$pd_idx', pd_idx = '".$p_info[0]."', pr_order = '".($i+1)."'");
	// }		


	// //옵션
	// $data = sql_fetch("select p.* from rb_product as p where p.pd_idx = '$pd_idx'");
	// $option = array();
	// $chk_option = array();
	// $option1 = make_product_option_value($data['pd_option1']);
	// $option2 = make_product_option_value($data['pd_option2']);
	// $stock_data = sql_list("select * from rb_product_stock where pd_idx = '$pd_idx'");
	// if(custom_count($option1) > 0 && custom_count($option2) == 0){
	// 	foreach($option1 as $row1){
	// 		$ps_option = $row1['o_name'];
	// 		$temp = array();
	// 		$temp['ps_option'] = $ps_option;
	// 		$option[] = $temp;
	// 		$chk_option[] = $pd_idx."|;|".$ps_option;
	// 	}
	// }else if(custom_count($option1) == 0 && custom_count($option2) > 0){
	// 	foreach($option2 as $row2){
	// 		$ps_option = $row2['o_name'];
	// 		$temp = array();
	// 		$temp['ps_option'] = $ps_option;
	// 		$option[] = $temp;
	// 		$chk_option[] = $pd_idx."|;|".$ps_option;
	// 	}
	// }else if(custom_count($option1) > 0 && custom_count($option2) > 0){
	// 	foreach($option1 as $row1){
	// 		foreach($option2 as $row2){
	// 			$ps_option = $row1['o_name'].":".$row2['o_name'];
	// 			$temp = array();
	// 			$temp['ps_option'] = $ps_option;
	// 			$option[] = $temp;
	// 			$chk_option[] = $pd_idx."|;|".$ps_option;
	// 		}
	// 	}
	// }else{
	// 	$ps_option = "";
	// 	$temp = array();
	// 	$temp['ps_option'] = $ps_option;
	// 	$option[] = $temp;
	// 	$chk_option[] = $pd_idx."|;|".$ps_option;
	// }

	// //사라진것 삭제
	// foreach($stock_data as $row){
	// 	$chk_txt = $row['pd_idx']."|;|".$row['ps_option'];
	// 	if(!in_array($chk_txt, $chk_option)){
	// 		sql_query("delete from rb_product_stock where ps_idx = '".$row['ps_idx']."'");
	// 	}
	// }

	// //추가
	// foreach($option as $row){
		
	// 	// 2019.12.30 허정진 임시수정
	// 	$ct_option_tmp = ("-"===$row['ps_option']) ? "" : $row['ps_option'];
	// 	$chk = sql_fetch("select * from rb_product_stock where pd_idx = '$pd_idx' and ps_option = '".$ct_option_tmp."'");
	// 	if(!$chk['ps_idx']){
	// 		sql_query("insert into rb_product_stock set pd_idx = '$pd_idx', ps_option = '".$row['ps_option']."' ");
	// 	}
	// }

	alert("상품이 추가되었습니다.", "./product_list.php?$query");
}else if($_POST['mode'] == "update"){

	$data = sql_fetch("select * from rb_product as b where b.pd_idx = '$pd_idx' $search_query");
	if(!$data['pd_idx']) alert("없는 상품입니다.");


	//파일삭제
	// foreach($field_arr as $k => $v){
	// 	if($_FILES[$v]['tmp_name']){
	// 		sql_query("update rb_product set $v = '', {$v}_org = '' where pd_idx = '$pd_idx'");
	// 		@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/".$data[$v]);
	// 		@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/thumb1_".$data[$v]);
	// 		@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/thumb2_".$data[$v]);
	// 	}
	// }

	//파일삭제
	for($i=0;$i<$_POST['file_cnt'];$i++){
		if($_POST["pd_file_del_".$i] == 1 && $_POST["fi_idx_".$i]){
			$f_data = sql_fetch("select * from rb_product_file where fi_idx = '".$_POST["fi_idx_".$i]."'");
			sql_query("delete from rb_product_file where fi_idx = '".$_POST["fi_idx_".$i]."'");
			@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/".$f_data['fi_name']);
			@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/thumb1_".$f_data['fi_name']);
			@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/thumb2_".$f_data['fi_name']);
		}
	}

	$sql = "update rb_product set
				$sql_common
				
				where pd_idx = '".$_POST['pd_idx']."'
			";
	$sql_q = sql_query($sql);
	$pd_idx = $_POST['pd_idx'];

	//파일저장
	$fi_num = 0;
	for($i=0;$i<$_POST['file_cnt'];$i++){
		if($_FILES["pd_file_" . $i]['tmp_name']){

			if($_POST["fi_idx_".$i]){
				$f_data = sql_fetch("select * from rb_product_file where fi_idx = '".$_POST["fi_idx_".$i]."'");
				sql_query("delete from rb_product_file where fi_idx = '".$_POST["fi_idx_".$i]."'");
				@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/".$f_data['fi_name']);
				@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/thumb1_".$f_data['fi_name']);
				@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/thumb2_".$f_data['fi_name']);
			}

			$src = $_FILES["pd_file_" . $i]['tmp_name'];
			$ext = strtolower(get_file_ext($_FILES["pd_file_" . $i]['name']));
			$tgt_name = md5(uniqid(rand(), TRUE)).".".$ext;
			$org_name = $_FILES["pd_file_" . $i]['name'];
			$tgt = $_cfg['web_home'].$_cfg['data_dir']."/files/".$tgt_name;
			$fi_size = filesize($src);

			Chk_exif_WH($src, $tgt);
			$thumb1 = $_cfg['web_home'].$_cfg['data_dir']."/files/thumb1_".$tgt_name;
			put_gdimage($tgt, $product_config['thumb1_size'], 0, $thumb1);

			$thumb2 = $_cfg['web_home'].$_cfg['data_dir']."/files/thumb2_".$tgt_name;
			put_gdimage($tgt, $product_config['thumb2_size'], 0, $thumb2);

			sql_query("insert into rb_product_file set fi_num = '$fi_num', pd_idx = '$pd_idx', fi_name = '$tgt_name', fi_name_org = '$org_name', fi_size = '$fi_size', fi_regdate = now() ");
			$fi_num++;
		}else{
			if($_POST["fi_idx_".$i]){
				$f_data = sql_fetch("select * from rb_product_file where fi_idx = '".$_POST["fi_idx_".$i]."'");
				if($f_data['fi_idx']){
					sql_query("update rb_product_file set fi_num = '$fi_num' where fi_idx = '".$_POST["fi_idx_".$i]."'");
					$fi_num++;
				}
			}
		}
	}

	//파일카운트
	$pd_file_cnt = sql_total("select * from rb_product_file where pd_idx = '$pd_idx'");
	sql_query("update rb_product set pd_file_cnt = '$pd_file_cnt' where pd_idx = '$pd_idx'");

	//파일저장
	// foreach($field_arr as $k => $v){
	// 	if($_FILES[$v]['tmp_name']){
	// 		$src = $_FILES[$v]['tmp_name'];
	// 		$ext = get_file_ext($_FILES[$v]['name']);
	// 		$tgt_name = md5(uniqid(rand(), TRUE)).".".$ext;
	// 		$org_name = $_FILES[$v]['name'];
	// 		$tgt = $_cfg['web_home'].$_cfg['data_dir']."/files/".$tgt_name;

	// 		Chk_exif_WH($src, $tgt);

	// 		$thumb1 = $_cfg['web_home'].$_cfg['data_dir']."/files/thumb1_".$tgt_name;
	// 		put_gdimage($tgt, $product_config['thumb1_size'], 0, $thumb1);

	// 		$thumb2 = $_cfg['web_home'].$_cfg['data_dir']."/files/thumb2_".$tgt_name;
	// 		put_gdimage($tgt, $product_config['thumb2_size'], 0, $thumb2);

	// 		sql_query("update rb_product set $v = '$tgt_name', {$v}_org = '$org_name' where pd_idx = '$pd_idx'");

	// 	}
	// }

	////카테고리3,4 정리
	// if(custom_count($_POST['pd_cate']) > 0){
	// 	//없어진것 삭제
	// 	$old_cate = sql_list("select * from rb_product_cate where pd_idx = '$pd_idx'");
	// 	for($i=0;$i<custom_count($old_data);$i++){
	// 		if(!in_array($old_cate[$i]['ca_idx'], $_POST['pd_cate'])){
	// 			sql_query("delete from rb_product_cate where pc_idx = '".$old_cate[$i]['pc_idx']."'");
	// 		}
	// 	}


	// 	for($i=0;$i<custom_count($_POST['pd_cate']);$i++){
	// 		$ca_idx = $_POST['pd_cate'][$i];
	// 		$chk = sql_fetch("select * from rb_product_cate where pd_idx = '$pd_idx' and ca_idx = '$ca_idx' ");
	// 		if(!$chk['pc_idx']){
	// 			sql_query("insert into rb_product_cate set ca_idx = '$ca_idx', pd_idx = '$pd_idx'");
	// 		}
			
	// 	}
	// }else{
	// 	sql_query("delete from rb_product_cate where pd_idx = '$pd_idx'");
	// }


	// //그룹상품
	// if($_POST['pd_group'] == 1){
	// 	sql_query("delete from rb_product_group where parent_idx = '$pd_idx'");
	// }else{
	// 	$product_group = $_POST['product_group'];
	// 	$product_group_arr = ($product_group != "") ? explode('|;|', $product_group) : array();
	// 	$product_group_data = array();
	// 	for($i=0;$i<custom_count($product_group_arr);$i++){
	// 		$p_info = ($product_group_arr[$i] != "") ? explode('|:|', $product_group_arr[$i]) : array();
	// 		$product_group_data[] = $p_info[0]."|;|".$p_info[2];
	// 	}		

	// 	//없어진것 삭제
	// 	$old_data = sql_list("select * from rb_product_group where parent_idx = '$pd_idx'");
	// 	for($i=0;$i<custom_count($old_data);$i++){
	// 		if(!in_array($old_data[$i]['pd_idx']."|;|".$old_data[$i]['pg_option'], $product_group_data)){
	// 			sql_query("delete from rb_product_group where pg_idx = '".$old_data[$i]['pg_idx']."'");
	// 		}
	// 	}

	// 	for($i=0;$i<custom_count($product_group_arr);$i++){
	// 		$p_info = ($product_group_arr[$i] != "") ? explode('|:|', $product_group_arr[$i]) : array();
	// 		$chk = sql_fetch("select * from rb_product_group where parent_idx = '$pd_idx' and pd_idx = '".$p_info[0]."' and pg_option = '".$p_info[2]."' ");
	// 		if(!$chk['pg_idx']){
	// 			sql_query("insert into rb_product_group set parent_idx = '$pd_idx', pd_idx = '".$p_info[0]."', pg_option = '".$p_info[2]."' , pg_cnt = '".$p_info[4]."', pg_order = '".($i+1)."'");
	// 		}else{
	// 			sql_query("update rb_product_group set pg_cnt = '".$p_info[4]."', pg_order = '".($i+1)."' where pg_idx = '".$chk['pg_idx']."'");
	// 		}
			
	// 	}
	// }

	// //연관상품
	// $product_relation = $_POST['product_relation'];
	// $product_relation_arr = ($product_relation != "") ? explode('|;|', $product_relation) : array();
	// $product_relation_data = array();
	// for($i=0;$i<custom_count($product_relation_arr);$i++){
	// 	$p_info = ($product_relation_arr[$i] != "") ? explode('|:|', $product_relation_arr[$i]) : array();
	// 	$product_relation_data[] = $p_info[0];
	// }		

	// //없어진것 삭제
	// $old_data = sql_list("select * from rb_product_relation where parent_idx = '$pd_idx'");
	// for($i=0;$i<custom_count($old_data);$i++){
	// 	if(!in_array($old_data[$i]['pd_idx'], $product_relation_data)){
	// 		sql_query("delete from rb_product_relation where pr_idx = '".$old_data[$i]['pr_idx']."'");
	// 	}
	// }

	// for($i=0;$i<custom_count($product_relation_arr);$i++){
	// 	$p_info = ($product_relation_arr[$i] != "") ? explode('|:|', $product_relation_arr[$i]) : array();
	// 	$chk = sql_fetch("select * from rb_product_relation where parent_idx = '$pd_idx' and pd_idx = '".$p_info[0]."' ");
	// 	if(!$chk['pr_idx']){
	// 		sql_query("insert into rb_product_relation set parent_idx = '$pd_idx', pd_idx = '".$p_info[0]."', pr_order = '".($i+1)."'");
	// 	}else{
	// 		sql_query("update rb_product_relation set pr_order = '".($i+1)."' where pr_idx = '".$chk['pr_idx']."'");
	// 	}
		
	// }


	// //옵션
	// $data = sql_fetch("select p.* from rb_product as p where p.pd_idx = '$pd_idx'");
	// $option = array();
	// $chk_option = array();
	// $option1 = make_product_option_value($data['pd_option1']);
	// $option2 = make_product_option_value($data['pd_option2']);
	// $stock_data = sql_list("select * from rb_product_stock where pd_idx = '$pd_idx'");
	// if(custom_count($option1) > 0 && custom_count($option2) == 0){
	// 	foreach($option1 as $row1){
	// 		$ps_option = $row1['o_name'];
	// 		$temp = array();
	// 		$temp['ps_option'] = $ps_option;
	// 		$option[] = $temp;
	// 		$chk_option[] = $pd_idx."|;|".$ps_option;
	// 	}
	// }else if(custom_count($option1) == 0 && custom_count($option2) > 0){
	// 	foreach($option2 as $row2){
	// 		$ps_option = $row2['o_name'];
	// 		$temp = array();
	// 		$temp['ps_option'] = $ps_option;
	// 		$option[] = $temp;
	// 		$chk_option[] = $pd_idx."|;|".$ps_option;
	// 	}
	// }else if(custom_count($option1) > 0 && custom_count($option2) > 0){
	// 	foreach($option1 as $row1){
	// 		foreach($option2 as $row2){
	// 			$ps_option = $row1['o_name'].":".$row2['o_name'];
	// 			$temp = array();
	// 			$temp['ps_option'] = $ps_option;
	// 			$option[] = $temp;
	// 			$chk_option[] = $pd_idx."|;|".$ps_option;
	// 		}
	// 	}
	// }else{
	// 	$ps_option = "";
	// 	$temp = array();
	// 	$temp['ps_option'] = $ps_option;
	// 	$option[] = $temp;
	// 	$chk_option[] = $pd_idx."|;|".$ps_option;
	// }

	// //사라진것 삭제
	// foreach($stock_data as $row){
	// 	$chk_txt = $row['pd_idx']."|;|".$row['ps_option'];
	// 	if(!in_array($chk_txt, $chk_option)){
	// 		sql_query("delete from rb_product_stock where ps_idx = '".$row['ps_idx']."'");
	// 	}
	// }

	// //추가
	// foreach($option as $row){

	// 	// 2019.12.30 허정진 임시수정
	// 	$ct_option_tmp = ("-"===$row['ps_option']) ? "" : $row['ps_option'];

	// 	$chk = sql_fetch("select * from rb_product_stock where pd_idx = '$pd_idx' and ps_option = '".$ct_option_tmp."'");
	// 	if(!$chk['ps_idx']){
	// 		sql_query("insert into rb_product_stock set pd_idx = '$pd_idx', ps_option = '".$row['ps_option']."' ");
	// 	}
	// }

	alert("상품이 수정되었습니다.", "./product_view.php?pd_idx=".$_POST['pd_idx']."&$query");
}else if($_POST['mode'] == "stock"){

	$data = sql_fetch("select * from rb_product as b where b.pd_idx = '$pd_idx' $search_query");
	if(!$data['pd_idx']) alert("없는 상품입니다.");

	if(custom_count($_POST['ps_idx']) > 0){
		for($i=0;$i<custom_count($_POST['ps_idx']);$i++){
			$ps_idx = $_POST['ps_idx'][$i];
			$ps_stock = $_POST['ps_stock_'.$ps_idx];
			sql_query("update rb_product_stock set ps_stock = '$ps_stock' where ps_idx = '".$ps_idx."' ");
		}
	}

	alert("재고가 수정되었습니다.", "./product_view.php?pd_idx=".$_POST['pd_idx']."&$query");

}else if($_GET['mode'] == "delete"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	$querys[] = "c1_idx=".$_GET['c1_idx'];
	$querys[] = "c2_idx=".$_GET['c2_idx'];
	$querys[] = "c3_idx=".$_GET['c3_idx'];
	
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	//echo "select * from rb_product as b left join rb_member as m on b.mb_id = m.mb_id where b.bc_code = '$bc_code' and b.pd_idx = '$pd_idx' $search_query";exit;

	$data = sql_fetch("select * from rb_product as b where b.pd_idx = '$pd_idx' $search_query");
	if(!$data['pd_idx']) alert("없는 상품입니다.");




	delete_product_article($pd_idx);

	alert("상품이 삭제되었습니다.", "./product_list.php?$query");

}else if($_GET['mode'] == "c_delete"){

	$querys = array();
	$querys[] = "page=".$page;
	$querys[] = "sca=".$_GET['sca'];
	$querys[] = "stx=".$_GET['stx'];
	if(is_array($product_config['category'])){
		if($_GET['pd_category']){
			$querys[] = "pd_category=".$_GET['pd_category'];
		}
	}
	$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

	$data = sql_fetch("select * from rb_product as b where b.pd_idx = '$pd_idx' $search_query");
	if(!$data['pd_idx']) alert("없는 상품입니다.");

	if(!$product_config['is_comment']) alert("잘못된 접근입니다.");

	$data2 = sql_fetch("select * from rb_product_comment where pd_idx = '$pd_idx' and cm_idx = '$cm_idx'");
	if(!$data2['cm_idx']) alert("없는 댓글입니다.");


	$sql = "delete from rb_product_comment where cm_idx = '$cm_idx'
			";
	$sql_q = sql_query($sql);

	alert("댓글이 삭제되었습니다.", "./product_view.php?pd_idx=".$_POST['pd_idx']."&$query");

}

alert("잘못된 접근입니다.", "./product_list.php?$query");
?>