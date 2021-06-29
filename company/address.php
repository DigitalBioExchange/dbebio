<?php
include "../inc/_common.php";

$t_menu = 3;
$l_menu = 3;

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'company/address.tpl',

	'left'  =>	'inc/cs_left.tpl',

	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
));


include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>