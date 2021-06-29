<?php
$page_name = "not-aside product";
$mobile_page_name = "";
include "../inc/_common.php";


$t_menu = 0;
$l_menu = 0;

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'shop/product_list.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
));
$product_config = $_cfg['product_config'];
$tpl->assign('product_config', $product_config); 


$querys = array();
$querys_page = array();


// GET 값 구해서 각각 필요에 따라 파라미터 만들기
if($_GET[page]){
	$page = $_GET[page];
}else{
	$page = 1;
}
$querys[] = "page=".$page;

if($_GET[c1_idx] != ""){
	$search_query .= " and p.c1_idx = '$_GET[c1_idx]' ";
}
$querys[] = "c1_idx=".$_GET[c1_idx];
$querys_page[] = "c1_idx=".$_GET[c1_idx];

if($_GET[c2_idx] != ""){
	$search_query .= " and p.c2_idx = '$_GET[c2_idx]' ";
}
$querys[] = "c2_idx=".$_GET[c2_idx];
$querys_page[] = "c2_idx=".$_GET[c2_idx];

if($_GET[c3_idx] != ""){
	$search_query .= " and p.c3_idx = '$_GET[c3_idx]' ";
}
$querys[] = "c3_idx=".$_GET[c3_idx];
$querys_page[] = "c3_idx=".$_GET[c3_idx];

if($_GET[s_price]){
	$search_query .= " and p.pd_price >= '$_GET[s_price]' ";
}
$querys[] = "s_price=".$_GET[s_price];
$querys_page[] = "s_price=".$_GET[s_price];

if($_GET[e_price]){
	$search_query .= " and p.pd_price <= '$_GET[e_price]' ";
}
$querys[] = "e_price=".$_GET[e_price];
$querys_page[] = "e_price=".$_GET[e_price];

if($_GET[stx]){
	$search_query .= " and p.pd_name like '%$_GET[stx]%' ";
}
$querys[] = "stx=".$_GET[stx];
$querys_page[] = "stx=".$_GET[stx];

$order_query = "order by p.pd_idx desc";
if($_GET[order_by]){
	switch($_GET[order_by]){
		case "new" :
			$order_query = "order by p.pd_idx desc";
		break;
		default :
			$order_query = "order by p.pd_idx desc";
		break;
	}
}
$querys[] = "order_by=".$_GET[order_by];
$querys_page[] = "order_by=".$_GET[order_by];

$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";
$query_page = (is_array($querys_page) && count($querys_page) > 0) ? implode("&", $querys_page) : "";


// 전체 데이터수 구하기
$sql_total = "select * from rb_product as p where p.pd_use = 1 $search_query";
$total = sql_total($sql_total);


//$total = 2367;
//$page = 46;
// 페이징 만들기 시작
$arr = array('total' => $total,
             'page' => $page,
             'row' => $product_config['list_row'],
             'scale' => $product_config['list_scale'],
             'center' => $_cfg['admin_paging_center'],
			 'link' => $query_page,
			 'page_name' => ""
        );

try {$paging = C::paging($arr); }
catch (Exception $e) {
    print 'LINE: '.$e->getLine().' '
                  .C::get_errmsg($e->getmessage());
    exit;
}
$tpl->assign($paging);
$tpl->assign('paging_data', $paging);

// 페이징 만들기 끝

if($total){
	$limit_query = " limit ".$paging['query']->limit." offset ".$paging['query']->offset;

	$sql = "select * from rb_product as p where p.pd_use = 1 $search_query $order_query $limit_query";
	$data = sql_list($sql);

	for($i=0;$i<count($data);$i++){
		$_pd_idx = $data[$i][pd_idx];
		if($_pd_idx){
			$_pd_file_data = sql_fetch("select * from rb_product_file where pd_idx = '$_pd_idx' and fi_num = 0");
			$data[$i][fi_name] = $_pd_file_data[fi_name];
			$data[$i][fi_name_org] = $_pd_file_data[fi_name_org];
			$data[$i][fi_idx] = $_pd_file_data[fi_idx];

		}
	}

	$tpl->assign('data', $data); 
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


include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>