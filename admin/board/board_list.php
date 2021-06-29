<?php
$menu_mode = "v";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'board/board_list.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'bottom'  =>'inc/bottom.tpl',
	'second_menu' => 'inc/second_menu.tpl',
));

$tpl->assign('page_title', get_txt_from_data($_cfg['menu_data'], $menu_code, "menu_code", "menu_name")."- 목록");

if(!in_array($bc_code, $_cfg['board']['bc_code'])){
	alert("없는 게시판입니다.");
}

$tpl->assign('bc_code', $bc_code); 

$board_config = $_cfg['board_config'][$bc_code];
$tpl->assign('board_config', $board_config); 

$querys = array();
$querys_page = array();
$querys[] = "bc_code=".$bc_code;
$querys_page[] = "bc_code=".$bc_code;


// GET 값 구해서 각각 필요에 따라 파라미터 만들기
if($_GET['page']){
	$page = $_GET['page'];
}else{
	$page = 1;
}
$querys[] = "page=".$page;

if(is_array($board_config['category']) && count($board_config['category']) > 0){
	if($_GET['bd_category']){
		$search_query .= "  and b.bd_category = '".$_GET['bd_category']."' ";
		$querys[] = "bd_category=".$_GET['bd_category'];
		$querys_page[] = "bd_category=".$_GET['bd_category'];
	}
}


if($_GET['sca'] && $_GET['stx']){
	switch($_GET['sca']){
		case "bd" : 
			$search_query .= " and (b.bd_title like '%".$_GET['stx']."%' or b.bd_contents like '%".$_GET['stx']."%') ";
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

$order_query = "order by b.bd_num desc";

$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";
$query_page = (is_array($querys_page) && count($querys_page) > 0) ? implode("&", $querys_page) : "";


// 전체 데이터수 구하기
$sql_total = "select * from rb_board as b left join rb_member as m on b.mb_id = m.mb_id where b.bc_code = '$bc_code' $search_query";
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

	$sql = "select *, if(b.mb_id = '' , b.bd_name, m.mb_name) as bd_writer from rb_board as b left join rb_member as m on b.mb_id = m.mb_id where b.bc_code = '$bc_code' $search_query $order_query $limit_query";
	$data = sql_list($sql);

	for($i=0;$i<count($data);$i++){
		$bd_idx = $data[$i]['bd_idx'];
		if($bd_idx){
			$bd_file_data = sql_fetch("select * from rb_board_file where bd_idx = '$bd_idx' and fi_num = 0");
			$data[$i]['fi_name'] = $bd_file_data['fi_name'];

			$chk = sql_fetch("select * from rb_board where bd_parent = '".$data[$i]['bd_idx']."'");
			$data[$i]['is_answer'] = ($chk['bd_idx']) ? 1 : 0;
		}
	}

	$tpl->assign('data', $data); 
}

$tpl->print_('body');
include "../inc/_tail.php";
?> 
