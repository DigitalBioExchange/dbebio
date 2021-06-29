<?php
$page_name = "not-aside product_view";
$mobile_page_name = "";
include "../inc/_common.php";


$t_menu = 0;
$l_menu = 0;

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'shop/product_view.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
));
$product_config = $_cfg['product_config'];
$tpl->assign('product_config', $product_config); 

$data = sql_fetch("select * from rb_product as p where p.pd_idx = '$pd_idx' and p.pd_use = 1 $search_query");

if(!$data[pd_idx]) alert("없는 상품입니다.");

$_GET[c1_idx] = $data[c1_idx];
$_GET[c2_idx] = $data[c2_idx];
$_GET[c3_idx] = $data[c3_idx];

$search_query = "";


$querys = array();
$querys[] = "page=".$page;
$querys[] = "c1_idx=".$_GET[c1_idx];
$querys[] = "c2_idx=".$_GET[c2_idx];
$querys[] = "c3_idx=".$_GET[c3_idx];
$querys[] = "s_price=".$_GET[s_price];
$querys[] = "e_price=".$_GET[e_price];
$querys[] = "stx=".$_GET[stx];
$querys[] = "order_by=".$_GET[order_by];
$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";



//옵션만들기
$data[option1] = make_product_option_value($data[pd_option1]);
$data[option2] = make_product_option_value($data[pd_option2]);

$tpl->assign('data', $data);

$pd_file_data = sql_list("select * from rb_product_file where pd_idx = '$pd_idx' order by fi_num asc");
$tpl->assign('pd_file_data', $pd_file_data);

//관련상품
$pd_relation = array();
if($data[pd_relation] != ""){
	$pd_relation_pd_idx_arr = explode(",", $data[pd_relation]);
	foreach($pd_relation_pd_idx_arr as $k => $v){
		$pd_relation[] = sql_fetch("select * from rb_product as p left join rb_cate1 as c1 on c1.c1_idx = p.c1_idx left join rb_cate2 as c2 on c2.c2_idx = p.c2_idx left join rb_cate3 as c3 on c3.c3_idx = p.c3_idx where pd_idx = '{$v}'");
	}

	for($i=0;$i<count($pd_relation);$i++){
		$_pd_idx = $pd_relation[$i][pd_idx];
		if($_pd_idx){
			$_pd_file_data = sql_fetch("select * from rb_product_file where pd_idx = '$_pd_idx' and fi_num = 0");
			$pd_relation[$i][fi_name] = $_pd_file_data[fi_name];
			$pd_relation[$i][fi_name_org] = $_pd_file_data[fi_name_org];
			$pd_relation[$i][fi_idx] = $_pd_file_data[fi_idx];

		}
	}
	$tpl->assign('pd_relation', $pd_relation); 
}


if($_cfg['function_list']['social_share']){
	$share_data[share_title] = $data[pd_name];
	$share_data[share_description] = mb_substr(strip_tags($data[pd_exp]), 0, 100, mb_internal_encoding());
	if($pd_file_data[0][fi_name]){
		$share_data[share_image] = "http://".$_SERVER[SERVER_NAME].$_cfg[data_dir]."/files/".$pd_file_data[0][fi_name];
	}
	$tpl->assign('share_data', $share_data);
}

//보기 기록
if($data[mb_id] != $member[mb_id]) sql_query("update rb_product set pd_view_cnt = pd_view_cnt + 1 where pd_idx = '$pd_idx' $search_query");

$view_where_query = ($is_member) ? "and mb_id = '".$member[mb_id]."'" : "and mb_session = '".session_id()."'";
$view_check = sql_fetch("select * from rb_product_view_history where pd_idx = '".$pd_idx."' $view_where_query");
if($view_check[ph_idx]){
	sql_query("update rb_product_view_history set ph_regdate = now() where pd_idx = '".$pd_idx."' $view_where_query");
}else{
	sql_query("insert into rb_product_view_history set pd_idx = '".$pd_idx."', mb_session = '".session_id()."', mb_id = '".$member[mb_id]."', ph_regdate = now() ");
}

if($_GET[c3_idx]){
	$c_data = sql_fetch("select * from rb_cate3 as c3 left join rb_cate2 as c2 on c3.c2_idx = c2.c2_idx left join rb_cate1 as c1 on c2.c1_idx = c1.c1_idx where c3_idx = '".$_GET[c3_idx]."'");
	$tpl->assign('c_data', $c_data);
	$tpl->assign('c3_idx', $c_data[c3_idx]);
	$tpl->assign('c2_idx', $c_data[c2_idx]);
	$tpl->assign('c1_idx', $c_data[c1_idx]);

	$sql = "select * from rb_cate3 where c2_idx = '".$c_data[c2_idx]."' order by c3_sort asc";
	$c3_data = sql_list($sql);

	$tpl->assign('c3_data', $c3_data); 


	$sql = "select * from rb_cate2 where c1_idx = '".$c_data[c1_idx]."' order by c2_sort asc";
	$c2_data = sql_list($sql);

	$tpl->assign('c2_data', $c2_data);

	$sql = "select * from rb_cate1 as c1 where 1 order by c1.c1_sort asc";
	$c1_data = sql_list($sql);

	$tpl->assign('c1_data', $c1_data); 
}else if($_GET[c2_idx]){
	$c_data = sql_fetch("select * from rb_cate2 as c2 left join rb_cate1 as c1 on c2.c1_idx = c1.c1_idx where c2_idx = '".$_GET[c2_idx]."'");
	$tpl->assign('c_data', $c_data);
	$tpl->assign('c2_idx', $c_data[c2_idx]);
	$tpl->assign('c1_idx', $c_data[c1_idx]);


	$sql = "select * from rb_cate2 where c1_idx = '".$c_data[c1_idx]."' order by c2_sort asc";
	$c2_data = sql_list($sql);

	$tpl->assign('c2_data', $c2_data);

	$sql = "select * from rb_cate1 as c1 where 1 order by c1.c1_sort asc";
	$c1_data = sql_list($sql);

	$tpl->assign('c1_data', $c1_data); 

	$sql = "select *, c3_idx as c_idx, c3_name as c_name, 'c3_idx' as idx_f from rb_cate3 where c2_idx = '".$c_data[c2_idx]."' order by c3_sort asc";
	$below_c_data = sql_list($sql);

	$tpl->assign('below_c_data', $below_c_data); 
}else if($_GET[c1_idx]){
	$c_data = sql_fetch("select * from rb_cate1 where c1_idx = '".$_GET[c1_idx]."'");
	$tpl->assign('c_data', $c_data);
	$tpl->assign('c1_idx', $c_data[c1_idx]);


	$sql = "select * from rb_cate1 as c1 where 1 order by c1.c1_sort asc";
	$c1_data = sql_list($sql);

	$tpl->assign('c1_data', $c1_data); 

	$sql = "select *, c2_idx as c_idx, c2_name as c_name, 'c2_idx' as idx_f from rb_cate2 where c1_idx = '".$c_data[c1_idx]."' order by c2_sort asc";
	$below_c_data = sql_list($sql);

	$tpl->assign('below_c_data', $below_c_data);

}

$sql = "select * from rb_manu as mn where 1 order by mn.mn_sort asc";
$mn_data = sql_list($sql);
$tpl->assign('mn_data', $mn_data); 

include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>