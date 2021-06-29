<?php
$menu_code = "800010";
$menu_mode = "w";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'product/wiki_insert.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'bottom'  =>'inc/bottom.tpl',
	'second_menu' => 'inc/second_menu.tpl',
));

$tpl->assign('page_title', get_txt_from_data($_cfg['menu_data'], $menu_code, "menu_code", "menu_name")."- 등록");
$tpl->assign('mode', "insert");

$wiki_config = $_cfg['wiki_config'];
$tpl->assign('wiki_config', $wiki_config); 

//커스텀필드 추가
foreach($_cfg['product_config']['custom_field'] as $row){
	$field_name = "wi_custom_field".$row['val'];
	$field_type = ($row['type'] == "select") ? "VARCHAR( 255 ) NOT NULL COMMENT  ''" : "INT( 11 ) NOT NULL DEFAULT  '0' COMMENT  ''";
	$filed_chk = sql_fetch("show columns from rb_wiki where Field = '".$field_name."'");
	if(!$filed_chk['Field']){
		$sql = "ALTER TABLE  `rb_wiki` ADD  `{$field_name}` {$field_type} ;";
		sql_query($sql);
	}
}


$search_query = "";


$querys = array();
$querys[] = "page=".$page;
$querys[] = "wi_type1=".$_GET['wi_type1'];
$querys[] = "wi_type2=".$_GET['wi_type2'];
$querys[] = "sca=".$_GET['sca'];
$querys[] = "stx=".$_GET['stx'];


$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

$tpl->assign('custom_field', $_cfg['product_config']['custom_field']); 

$tpl->print_('body');
include "../inc/_tail.php";
?> 
