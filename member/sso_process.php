<?php
include "../inc/_common.php";
include "../inc/_head.php";

already_logged();


//p_arr($_POST);
set_session("sso_info" , $_POST);

//p_arr($_SESSION['sso_info']);

//$chk_member1 = sql_fetch("select * from rb_member where mb_id = '".$_SESSION['sso_info']['onlId']."' or mb_onlCno = '".$_SESSION['sso_info']['onlCno']."' ");
$chk_member1 = sql_fetch("select * from rb_member where mb_onlCno = '".$_SESSION['sso_info']['onlCno']."' ");
if($chk_member1['mb_id']){
	if($chk_member1['mb_status'] == 3){
		$_SESSION['sso_info']['use_id'] = 0;
		$next_process = "join";
	}else{
		$next_process = "login";
		$mb = $chk_member1;
	}
}else{
	$chk_member2 = sql_fetch("select * from rb_member where mb_id = '".$_SESSION['sso_info']['onlId']."' ");
	if($chk_member2['mb_id']){
		$_SESSION['sso_info']['use_id'] = 0;
		$next_process = "join";
	}else{
		$_SESSION['sso_info']['use_id'] = 1;
		$next_process = "join";
	}
}

if($next_process == "login"){
	$_SESSION['ss_mb_idx'] = $mb['mb_idx'];
	$_SESSION['ss_mb_id'] = $mb['mb_id'];
	$_SESSION['ss_mb_nick'] = $mb['mb_nick'];
	$_SESSION['ss_mb_level'] = $mb['mb_level'];

	sql_query("update rb_member set mb_lastlogin = mb_nowlogin where mb_id = '".$mb['mb_id']."'");
	sql_query("update rb_member set mb_nowlogin = now() where mb_id = '".$mb['mb_id']."'");

	sql_query("insert into rb_login_history set mb_id = '".$mb['mb_id']."', lh_regdate = now(), lh_year = '".date("Y")."', lh_month = '".date("n")."', lh_day = '".date("j")."'");

	$url = (!$url) ? "/" : $url;


	if($user_agent == "app"){

		if($user_br == "And"){
		?>
		<script language='JavaScript'>
			window.lusoft.hybridGetMbid('<?=mb['mb_id']?>');
		</script>
		<?
		}else if($user_br == "iOS"){
		?>
		<script language='JavaScript'>
			var sendObjectMessage_obj = {
					mb_id: '<?=mb['mb_id']?>'
				}
			window.webkit.messageHandlers.hybridGetMbid.postMessage(JSON.stringify(sendObjectMessage_obj));
		</script>
		<?
		}

	}
	goto_url($url);
	unset($_SESSION['sso_info']);
}else if($next_process == "join"){
	goto_url("/member/join.php?mb_level=1");
}

alert("잘못된 접근입니다.", "/index.php");



//goto_url("/member/join.php?mb_level=1");



include "../inc/_tail.php";
?>