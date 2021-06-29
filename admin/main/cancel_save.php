<?php
// ini_set("memory_limit" , -1);
$menu_code = "500100";
$menu_mode = "w";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

// p_arr($_POST);exit;

if ($_POST['mode'] == 'update_cancel') {
	$sql = "select * from rb_board where bd_idx = '".$bd_idx."' and od_idx = '".$od_idx."' ";
	$data = sql_fetch($sql);

	if ($data['bd_idx']) {
		if ($bd_answer == $data['bd_answer']) {
			$sql_upd = "update rb_board set bd_answer = '".$bd_answer."' where bd_idx = '".$bd_idx."' ";
		} else {
			$sql_upd = "update rb_board set bd_answer = '".$bd_answer."', bd_answer_regdate = now() where bd_idx = '".$bd_idx."' ";
		}
		sql_query($sql_upd);
		$sql_upd = "update rb_order set od_status = '".$od_status."' where od_idx = '".$od_idx."' ";
		sql_query($sql_upd);
		$sql_upd = "update rb_order_cart set ct_status = '".$od_status."' where od_idx = '".$od_idx."' ";
		sql_query($sql_upd);
		alert("적용되었습니다.", "/admin/main/cancel_list.php?$query");
	} else {
		alert("없는 정보입니다.");
	}


}

alert("잘못된 접근입니다.", "./cancel_view.php?$query");
?>