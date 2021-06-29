<?php
$menu_code = "400119";
$menu_mode = "v";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'product/cate_list.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'bottom'  =>'inc/bottom.tpl',
	'second_menu' => 'inc/second_menu.tpl',
));

$tpl->assign('page_title', get_txt_from_data($_cfg['menu_data'], $menu_code, "menu_code", "menu_name")."- 목록");

$data = sql_list("select * from rb_cate where ca_step = 1 order by ca_sort asc");
for($i=0;$i<custom_count($data);$i++){
	$data[$i]['child'] = sql_list("select * from rb_cate where ca_step = 2 and parent_idx = '".$data[$i]['ca_idx']."' order by ca_sort asc");
	for($j=0;$j<custom_count($data[$i]['child']);$j++){
		$data[$i]['child'][$j]['child'] = sql_list("select * from rb_cate where ca_step = 3 and parent_idx = '".$data[$i]['child'][$j]['ca_idx']."' order by ca_sort asc");
			for($k=0;$k<custom_count($data[$i]['child'][$j]['child']);$k++){
				$data[$i]['child'][$j]['child'][$k]['child'] = sql_list("select * from rb_cate where ca_step = 4 and parent_idx = '".$data[$i]['child'][$j]['child'][$k]['ca_idx']."' order by ca_sort asc");
			}

	}
}
$tpl->assign('data', $data); 

$tpl->print_('body');
include "../inc/_tail.php";
?> 
