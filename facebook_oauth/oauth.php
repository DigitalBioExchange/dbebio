<?php
include "../inc/_common.php";
include "../inc/_head.php";

//#######################################
$_fb_app = array(
	'id' => $_cfg['function_list']['social_login_facebook_code']
	,'secret' => $_cfg['function_list']['social_login_facebook_secret']
	,'url' => "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']
);

function getLoginUrl($_fb_app){
	$mt = microtime();
	$rand = mt_rand();
	$_state = md5($mt . $rand);
	$_SESSION["fb_state"]=$_state;
	$_ret_url =
	$_param = array(
		'client_id' => $_fb_app['id']
		,'response_type' => 'code'
		,'redirect_uri' => $_fb_app['url']
		//,'scope' => 'publish_stream,user_website,email,offline_access'
		,'scope' => 'email'
	);

	$_login_url = "https://www.facebook.com/v2.9/dialog/oauth?".http_build_query($_param);
	return $_login_url;
}
function getAccessToken($_code,$_fb_app){

	$_param = array(
		'client_id' => $_fb_app['id']
	   	,'client_secret' => $_fb_app['secret']
		,'redirect_uri' => $_fb_app['url']
		,'code' => $_code
	);
	$_url = "https://graph.facebook.com/v2.9/oauth/access_token?".http_build_query($_param);

	$curlObj = curl_init();
	curl_setopt($curlObj, CURLOPT_URL, $_url);
	curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($curlObj, CURLOPT_HEADER, 0);
	$response = curl_exec($curlObj);
	$_json = json_decode($response,true);
	curl_close($curlObj);
	return array(
		'response' => $response
		,'json' => $_json
		,'_param' => $_param
	);
}
function getUserMe($_token,$_fb_app){

	$_param = array(
		//'client_id' => $_fb_app['id']
		//,'client_secret' => $_fb_app['secret']
		'fields' => 'about,cover,email,birthday,picture'
		,'access_token' => $_token
	);
	$_url = "https://graph.facebook.com/v2.9/me?".http_build_query($_param);

	$curlObj = curl_init();
	curl_setopt($curlObj, CURLOPT_URL, $_url);
	curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($curlObj, CURLOPT_HEADER, 0);
	$response = curl_exec($curlObj);
	$_json = json_decode($response,true);
	curl_close($curlObj);
	return array(
		'response' => $response
		,'json' => $_json
		,'_param' => $_param
	);

}


//=====================================
if($_GET['action'] == 'login'){
	$_login_url = getLoginUrl($_fb_app);
	echo "<script>location.href = '".$_login_url."'</script>";	
	exit;
}
if(isset($_GET['code'])){
	$_code = $_GET['code'];
	if (!isset($_SESSION['fb_token'])){
		$_res = getAccessToken($_code,$_fb_app);
		$_token = $_res['json']['access_token'];
		$_SESSION['fb_token'] = $_token;
	}
	echo "<script>location.href = '?action=users'</script>";
	
}

if (isset($_SESSION['fb_token']) && $_SESSION['fb_token'] !=''){
	$_token = $_SESSION['fb_token'];
	$_users =  getUserMe($_token,$_fb_app);
	// echo "유저정보";
	// print_r_text($_users);
}else{
	// print_r_text($_SESSION);
	echo "<script>location.href = '?action=login'</script>";
}

?>
<script>
	window.onload=function(){
		// callback이 오면 checkLoginState()함수를 호출한다.
		checkLoginState();
	}

	function checkLoginState() {
		var ss_id = '<?=$_users[json][id]?>'; //: 사용자 정보 사용자 식별값
		var access_token = '<?=$_token?>';
		var ss_email = ''; // 사용자 이메일
		var ss_photo = '<?=$_users[picture][data][url]?>';  // : 사용자 정보 프로필이미지URL

		$.post('/inc/sns_check.php', 
			{
				ss_from : 'facebook',
				action : 'login',
				access_token : access_token,
				ss_id : ss_id,
				ss_email : ss_email,
				ss_photo : ss_photo
			},
			function(data_var){
					//결과가 없을시 에러처리
					if(data_var.result == ""){
							self.close();
							return false;
					}else{
						//에러일경우 에러처리
						if(data_var.result == "error"){
							self.close();
							if(data_var.msg) alert(data_var.msg);
							return false;
						}else if(data_var.result == "success"){
							if(data_var.url){opener.location.href=data_var.url;}
							self.close();
							if(data_var.msg) alert(data_var.msg);
							return true;
						}else{
							self.close();
							alert("잘못된 접근입니다.");
							return false;
						}
					}
			},'json');
	}
</script>
