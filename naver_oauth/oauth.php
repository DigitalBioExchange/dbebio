<?php
$menu_num = 0;
include "../inc/_common.php";
include "../inc/_head.php";

if($_GET[action]) $_SESSION['action'] = $_GET[action];
?>
<script type="text/javascript" 
charset="utf-8" src="https://static.nid.naver.com/js/naverLogin.js" ></script>
<script type="text/javascript">
    function generateState() {
        // CSRF 방지를 위한 state token 생성 코드
        // state token은 추후 검증을 위해 세션에 저장 되어야 합니다.
        var oDate = new Date();
        return oDate.getTime();
    }
    function saveState(state) {
        //$.removeCookie("state_token");
        $.cookie("state_token", state);
    }
    var naver = NaverAuthorize({
        client_id : "<?=$_cfg['function_list']['social_login_naver_id']?>",
        redirect_uri : "http://<?=$_SERVER["HTTP_HOST"]?>/naver_oauth/oauth.php",
        client_secret : "<?=$_cfg['function_list']['social_login_naver_key']?>"
    });


     function loginNaver() {
        var state = generateState();
        saveState(state);
        naver.login(state);
    }

    $("#NaverIdLoginBTN").click( function () {
        var state = generateState();
        saveState(state);
        naver.login(state);
    });
</script>
<script type="text/javascript">

 function id_check(access_token){
  var URL = "https://apis.naver.com/nidlogin/nid/getUserProfile.json?response_type=json";
  naver.api(URL, access_token, function(data) {
	  var response = data._response.responseJSON;
	  
	  response.resultcode; //: API호출 결과
	  response.message;    //: API호출 결과 메시지 
	  response.response;    //: API호출 결과 데이터(사용자정보)
	  
	  var ss_id = response.response.id; //: 사용자 정보 사용자 식별값
	  var ss_email = response.response.email; // 사용자 이메일
	  var ss_photo = response.response.profile_image;  // : 사용자 정보 프로필이미지URL
	  console.log(response);

		<?if($_SESSION[action] == "login"){?>
		$.post('/inc/sns_check.php', 
		{
			ss_from : 'naver',
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
						if(data_var.msg) alert(data_var.msg);
						self.close();
						return true;
					}else{
						self.close();
						alert("잘못된 접근입니다.");
						return false;
					}
				}
		},'json');
		<?}else if($_SESSION[action] == "link"){?>
		$.post('/inc/sns_check.php', 
		{
			ss_from : 'naver',
			action : 'link',
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
						$('#onoff_naver', opener.document).addClass('color-primary');
						if(data_var.url){opener.location.href=data_var.url;}
						if(data_var.msg) alert(data_var.msg);
						self.close();
						return true;
					}else{
						self.close();
						alert("잘못된 접근입니다.");
						return false;
					}
				}
		},'json');
		<?}?>

  });
 }


    window.onload=function(){
 // callback이 오면 checkLoginState()함수를 호출한다.
        checkLoginState();
    }
    var tokenInfo = { access_token:"" , refresh_token:"" };
    function checkLoginState() {
			var state = $.cookie("state_token");
			if(naver.checkAuthorizeState(state) === "connected") {
					//정상적으로 Callback정보가 전달되었을 경우 Access Token발급 요청 수행
					naver.getAccessToken(function(data) {

							var response = data._response.responseJSON;
							if(response.error === "fail") {
									//access token 생성 요청이 실패하였을 경우에 대한 처리
									return ;
							}
							var access_token = response.access_token;
							var refresh_token = response.refresh_token;
							//alert(access_token);

							//sonsole.log에 나온다.
							console.log(access_token);

							id_check(access_token);




					});
			} else {
					//Callback으로 전달된 데이터가 정상적이지 않을 경우에 대한 처리
					return ;
			}
	}

	<?if($_GET[action]){?>loginNaver();<?}?>
</script>
<?
include "../inc/_tail.php";
?>