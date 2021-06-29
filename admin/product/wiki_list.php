<?php
$menu_code = "800010";
$menu_mode = "v";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'product/wiki_list.tpl',
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

if($_GET['wi_type1'] != ""){
	$search_query .= " and mn.wi_type1 = '".$_GET['wi_type1']."' ";
}
$querys[] = "wi_type1=".$_GET['wi_type1'];
$querys_page[] = "wi_type1=".$_GET['wi_type1'];

if($_GET['wi_type2'] != ""){
	$search_query .= " and mn.wi_type2 = '".$_GET['wi_type2']."' ";
}
$querys[] = "wi_type2=".$_GET['wi_type2'];
$querys_page[] = "wi_type2=".$_GET['wi_type2'];

if($_GET['sca'] && $_GET['stx']){
	switch($_GET['sca']){
		case "bd" : 
			$search_query .= " and (p.wi_name like '%".$_GET['stx']."%' or p.wi_contents like '%".$_GET['stx']."%') ";
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

$order_query = "order by mn.wi_idx desc";

$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";
$query_page = (is_array($querys_page) && count($querys_page) > 0) ? implode("&", $querys_page) : "";


// 전체 데이터수 구하기
$sql_total = "select * from rb_wiki as mn where 1 $search_query";
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

	$sql = "select * from rb_wiki as mn where 1 $search_query $order_query $limit_query";
	$data = sql_list($sql);

	$tpl->assign('data', $data); 
}
/*
foreach($_cfg['product_config']['custom_field'] as $row){
	$field_name = "wi_custom_field".$row['val'];
	$field_type = ($row['type'] == "select") ? "VARCHAR(255) NOT NULL" : "smallint(4) NOT NULL DEFAULT '0'";
	$field_comment = $row['txt'];
	//echo "ALTER TABLE rb_product ADD {$field_name} {$field_type} COMMENT '{$field_comment}' <br>";
	//sql_query("ALTER TABLE rb_wiki ADD {$field_name} {$field_type} COMMENT '{$field_comment}' ");
}
*/

$tpl->print_('body');
include "../inc/_tail.php";
?> 
