<?php
$menu_num = 0;
include "../inc/_common.php";
include "../inc/_head.php";


$t_menu = 5;
$l_menu = 13;


$tpl=new Template;
$tpl->define(array(
    'contents'  =>'shop/review_list.tpl',

	'left'  =>	'inc/cs_left.tpl',

	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
));

$tpl->assign('page_title', '포토리뷰');

$querys = array();
$querys_page = array();

// GET 값 구해서 각각 필요에 따라 파라미터 만들기
if($_GET[page]){
	$page = $_GET[page];
}else{
	$page = 1;
}
$querys[] = "page=".$page;

$search_query = "";

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

if($_GET[stx]){
	$search_query .= " and p.pd_name like '%$_GET[stx]%' ";
}

$querys[] = "stx=".$_GET[stx];
$querys_page[] = "stx=".$_GET[stx];


$order_query = "order by r.pr_idx desc";

$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";
$query_page = (is_array($querys_page) && count($querys_page) > 0) ? implode("&", $querys_page) : "";

// 전체 데이터수 구하기
$sql_total = "select * from rb_product_review as r left join rb_product as p on r.pd_idx = p.pd_idx where r.pr_img != ''  $search_query";
$total = sql_total($sql_total);


//$total = 2367;
//$page = 46;
// 페이징 만들기 시작
$arr = array('total' => $total,
             'page' => $page,
             'row' => 8,
             'scale' => $_cfg['admin_paging_scale'],
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

	$sql = "select * from rb_product_review as r left join rb_product as p on r.pd_idx = p.pd_idx where r.pr_img != '' $search_query $order_query $limit_query";
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

$sql = "select * from rb_cate1 as c1 where 1 order by c1.c1_sort asc";
$c1_data = sql_list($sql);

$tpl->assign('c1_data', $c1_data); 

$tpl->print_('body');
include "../inc/_tail.php";
?>