<?php
include "../inc/_common.php";

set_cookie('user_id_auto', '', 3600*24*365);
set_cookie('user_id_pass', '', 3600*24*365);
set_cookie('mb_ck', '', 3600*24*365);


if($_SESSION['chk_user_id']){
	unset($_SESSION['chk_user_id']);
	?>
	<script>
		self.close();
	</script>
	<?
	exit;
}else{
	unset($_SESSION['ss_mb_idx']);
	unset($_SESSION['ss_mb_id']);
	unset($_SESSION['ss_mb_name']);
	unset($_SESSION['ss_mb_level']);
}

if($user_agent == "app"){
	if($user_br == "And"){
	?>
		<script language='JavaScript'>
			// window.lusoft.set_back_key(4);
			// window.lusoft.window_finish();
			window.lusoft.setUserInfo("<?=$sv_code?>", "");
			window.lusoft.window_finish();
		</script>
	<?
	}else if($user_br == "iOS"){
	?>
		<script language='JavaScript'>
			// var sendObjectMessage_obj = {
			// 		back_state: 4
			// 	}
			// window.webkit.messageHandlers.set_back_key.postMessage(JSON.stringify(sendObjectMessage_obj));
			var sendObjectMessage_obj = {
					key: "<?=$sv_code?>",
					value: "",
			}
			window.webkit.messageHandlers.setUserInfo.postMessage(JSON.stringify(sendObjectMessage_obj));
			window.webkit.messageHandlers.window_finish.postMessage(null);
		</script>
	<?
	}
} else {
	goto_url("/");
}
?>