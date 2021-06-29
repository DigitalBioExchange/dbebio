<?php
include "../inc/_common.php";
include "../inc/_head.php";

// 인증코드로 사용자 토큰 얻기
$CLIENT_ID     = $_cfg['function_list']['social_login_kakaotalk_restapi'];
$REDIRECT_URI  = "http://".$_cfg['host_name']."/kakao_oauth/oauth.php";
$TOKEN_API_URL = "https://kauth.kakao.com/oauth/authorize?client_id=$CLIENT_ID&redirect_uri=$REDIRECT_URI&response_type=code";

// print_r($output);
// exit;
if ($_GET[action] == 'login') {
	echo "<script>location.href = '".$TOKEN_API_URL."'</script>";	
} else {
	// echo $_GET[code];
 
	$TOKEN_API_URL = "https://kauth.kakao.com//oauth/token";
	$code   = $_GET[code];
	$params = sprintf( 'grant_type=authorization_code&client_id=%s&redirect_uri=%s&code=%s', $CLIENT_ID, $REDIRECT_URI, $code);

	$opts = array(
	   CURLOPT_URL => $TOKEN_API_URL,
	   CURLOPT_SSL_VERIFYPEER => false,
	   CURLOPT_SSLVERSION => 1,
	   CURLOPT_POST => true,
	   CURLOPT_POSTFIELDS => $params,
	   CURLOPT_RETURNTRANSFER => true,
	   CURLOPT_HEADER => false
	);
	 
	$curlSession = curl_init();
	curl_setopt_array($curlSession, $opts);
	$accessTokenJson = curl_exec($curlSession);
	curl_close($curlSession);
	$user = json_decode($accessTokenJson);

	//엑세스토큰을 사용하여 사용자 프로필 가져오기
	$TOKEN_API_URL = "https://kapi.kakao.com/v1/user/me";
	 
	$opts = array(
	   CURLOPT_URL => $TOKEN_API_URL,
	   CURLOPT_SSL_VERIFYPEER => false,
	   CURLOPT_SSLVERSION => 1,
	   CURLOPT_POST => true,
	   CURLOPT_POSTFIELDS => false,
	   CURLOPT_RETURNTRANSFER => true,
	   CURLOPT_HTTPHEADER => array(
	"Authorization: Bearer " . $user->access_token
	)
	);
	 
	$curlSession = curl_init();
	curl_setopt_array($curlSession, $opts);
	$accessTokenJson = curl_exec($curlSession);
	curl_close($curlSession);
	$userInfo = json_decode($accessTokenJson);
}

// print_r($userInfo);

// echo $user->access_token."<br>";
// echo $userInfo->id."<br>";
// echo $userInfo->properties->profile_image."<br>";
?>

<script type="text/javascript">

	window.onload=function(){
		// callback이 오면 checkLoginState()함수를 호출한다.
		checkLoginState();
	}

	function checkLoginState() {
		ss_id = '<?=$userInfo->id?>';
		ss_email = '<?=$userInfo->kaccount_email?>';
		ss_photo = '<?=$userInfo->properties->profile_image?>';
		access_token = '<?=$user->access_token?>';

		$.post('/inc/sns_check.php', 
		{
			ss_from : 'kakao',
			action : 'login',
			access_token : access_token,
			ss_id : ss_id,
			ss_email : ss_email,
			ss_photo : ss_photo
		},
		function(data_var){
			console.log(data_var);
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
						self.close();
						if(data_var.url){opener.location.href=data_var.url;}
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