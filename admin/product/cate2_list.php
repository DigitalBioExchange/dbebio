<?php
$menu_code = "400112";
$menu_mode = "v";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'product/cate2_list.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'bottom'  =>'inc/bottom.tpl',
	'second_menu' => 'inc/second_menu.tpl',
));

$tpl->assign('page_title', get_txt_from_data($_cfg['menu_data'], $menu_code, "menu_code", "menu_name")."- 목록");


$querys = array();
$querys_page = array();


// GET 값 구해서 각각 필요에 따라 파라미터 만들기
if($_GET['page']){
	$page = $_GET['page'];
}else{
	$page = 1;
}
$querys[] = "page=".$page;



if($_GET['sca'] && $_GET['stx']){
	switch($_GET['sca']){
		case "bd" : 
			$search_query .= " and (p.c2_name like '%".$_GET['stx']."%' or p.c2_contents like '%".$_GET['stx']."%') ";
		break;
		default:
			$search_query .= " and ".$_GET['sca']." like '%".$_GET['stx']."%' ";
		break;
	}
}

$querys[] = "sca=".$_GET['sca'];
$querys_page[] = "sca=".$_GET['sca'];
$querys[] = "stx=".$_GET['stx'];
$querys_page[] = "stx=".$_GET['stx'];

if($_GET['c1_idx'] != ""){
	$search_query .= " and c2.c1_idx = '".$_GET['c1_idx']."' ";
}
$querys[] = "c1_idx=".$_GET['c1_idx'];
$querys_page[] = "c1_idx=".$_GET['c1_idx'];

$order_query = "order by c1.c1_sort asc, c2.c2_sort asc";

$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";
$query_page = (is_array($querys_page) && count($querys_page) > 0) ? implode("&", $querys_page) : "";


// 전체 데이터수 구하기
$sql_total = "select * from rb_cate2 as c2 where 1 $search_query";
$total = sql_total($sql_total);


//$total = 2367;
//$page = 46;
// 페이징 만들기 시작
$arr = array('total' => $total,
             'page' => $page,
             'row' => $_cfg['admin_paging_row'],
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

	$sql = "select * from rb_cate2 as c2 left join rb_cate1 as c1 on c1.c1_idx = c2.c1_idx where 1 $search_query $order_query $limit_query";
	$data = sql_list($sql);

	$tpl->assign('data', $data); 
}

$sql = "select * from rb_cate1 as c1 where 1 order by c1.c1_sort asc";
$c1_data = sql_list($sql);

$tpl->assign('c1_data', $c1_data); 

$tpl->print_('body');
include "../inc/_tail.php";
?> 
