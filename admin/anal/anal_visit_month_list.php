<?php
$menu_code = "900210";
$menu_mode = "v";

$limit_access = "";
$limit_access_level = "worker";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'anal/anal_visit_month_list.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'bottom'  =>'inc/bottom.tpl',
	'second_menu' => 'inc/second_menu.tpl',
));


$tpl->assign('page_title', get_txt_from_data($_cfg['menu_data'], $menu_code, "menu_code", "menu_name"));


$s_year = ($s_year) ? $s_year : date("Y");
$s_str = $s_year;

$querys = array();
$querys_page = array();

$search_query = " and SUBSTRING(lh_regdate, 1, 4) >= '$s_str' ";


$querys[] = "s_year=".$s_year;
$querys_page[] = "s_year=".$s_year;

$querys[] = "s_month=".$s_month;
$querys_page[] = "s_month=".$s_month;

$order_query = "order by m.mb_idx desc";

$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";
$query_page = (is_array($querys_page) && count($querys_page) > 0) ? implode("&", $querys_page) : "";

// 전체 데이터수 구하기
$sql_total = "select * from rb_login_history  where 1 $search_query";
$total = sql_total($sql_total);

$sql = " select count(*) as cnt, SUBSTRING(lh_regdate, 1, 7) as da
          from rb_login_history  where 1
          $sql_search
		  group by SUBSTRING(lh_regdate, 1, 7)
		  order by lh_regdate asc
		";
$data1 = sql_list($sql);

$max = 20;
for ($i=0; $i<count($data1); $i++) {
	if($data1[$i]['cnt'] > $max) $max = $data1[$i]['cnt'];
}

$data = array();
for ($i=1; $i<=12; $i++) {
	
	$date = $s_str."-".make_zero_first($i, 2);
	$cnt = get_txt_from_data($data1, $date, "da", "cnt");
	$cnt = ($cnt) ? $cnt : 0;
	//$cnt = ($i > $max) ? $max : $i;
	$percent = (int)($cnt * 100 / $max);

	$tmp = array();
	$tmp['date'] = $date;
	$tmp['cnt'] = $cnt;
	$tmp['percent'] = $percent;
	$data[] = $tmp;
}

$tpl->assign('data', $data);


//For graph - added, 20130430
$data_json[0][] = 'Date';
$data_json[0][] = 'Count';
if(count($data)) {
	foreach($data as $k => $v) {
		$data_json[$k+1][] = $v['date'];
		$data_json[$k+1][] = intval($v['cnt']);
	}
}
$tpl->assign('data_json', json_encode($data_json));


$tpl->print_('body');
include "../inc/_tail.php";
?> 
