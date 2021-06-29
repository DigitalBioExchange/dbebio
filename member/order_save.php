<?php
include "../inc/_common.php";
include "../inc/_head.php";

goto_login();

if($mode == 'finish') {
	$sql = "select * from rb_order where od_idx = '".$od_idx."' and od_status in (4, 5) and mb_idx = '".$member['mb_idx']."' ";
	$data = sql_fetch($sql);

	if ($data['od_idx'] && $od_status == '9') {
		$sql_upd = "update rb_order set od_status = 9 where od_idx = '".$od_idx."' ";
		sql_query($sql_upd);
		$sql_upd = "update rb_order_cart set ct_status = 9 where od_idx = '".$od_idx."' ";
		sql_query($sql_upd);

		goto_url("/member/order_view.php?od_idx=$od_idx");
	} else {
		alert($_lang['inc']['text_0746']);
	}
}

// $od_amount = get_txt_from_data($_cfg['order']['payment'], $_POST['od_pd'], 'val', 'price');

// $sql_common = "
// 	mb_id = '".$member['mb_id']."',
// 	od_num = '".$_POST['od_num']."',
// 	od_pd = '".$_POST['od_pd']."',
// 	od_paymethod = '".$_POST['od_paymethod']."',
// 	od_ipgum_bank = '".$_POST['od_ipgum_bank']."',
// 	od_ipgum_name = '".$_POST['od_ipgum_name']."',
// 	od_ipgum_num = '".$_POST['od_ipgum_num']."',
// 	od_amount = '".$od_amount."'
// ";


// $sql = "insert into rb_order set
// 			$sql_common,
// 			od_regdate = now()
// 		";
// $sql_q = sql_query($sql);

// alert("무통장입금 결제가 신청되었습니다..", "/member/order_list.php");

alert($_lang['tpl_shop']['text_0363']);
include "../inc/_tail.php";
?>