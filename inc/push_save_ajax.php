<?
include "../_inc/_common.php";
header("Content-Type: application/json;charset=utf-8");

$_is_ajax = false;
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
	&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ){
		$_is_ajax = true;
}

if ($_is_ajax != true) {
	$arr = array();
	$arr['result'] = "error";
	$arr['msg'] = "".$_lang['inc']['text_0696']."";
	echo json_encode($arr);exit;
}

if ($is_member) {

	if ($val == 1) {
		$sql = "update rb_member set mb_push = '0' where mb_idx = '".$member['mb_idx']."' ";
	} else {
		$sql = "update rb_member set mb_push = '1' where mb_idx = '".$member['mb_idx']."' ";
	}
	sql_query($sql);

	$arr = array();
	$arr['result'] = "success";
	$arr['msg'] = '';
	echo json_encode($arr);exit;

} else{
	$arr = array();
	$arr['result'] = "error";
	$arr['msg'] = $_lang['tpl_member']['text_0121'];
	echo json_encode($arr);exit;

}

// $chk = sql_fetch("select * from rb_cate1 as c1 where c1.c1_idx = '$c1_idx' ");
// if(!$chk['c1_idx']){
// 	$arr = array();
// 	$arr['result'] = "error";
// 	$arr['msg'] = "없는 카테고리입니다.";
// 	echo json_encode($arr);exit;
// }

// $sql = "select * from rb_cate2 as c2 where c1_idx = '$c1_idx' order by c2.c2_sort asc";
// $data = sql_list($sql);
?>