<?php
$menu_num = 0;
include "../inc/_common.php";
include "../inc/_head.php";
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
        $.removeCookie("state_token");
        $.cookie("state_token", state);
    }
    var naver = NaverAuthorize({
        client_id : "p38xqswd6S0pCNKzEZoZ",
        redirect_uri : "http://www.whattong.com/naver_oauth/oauth.php",
        client_secret : "73XTXvIkkT"
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
							console.log("success to get access token", response);


							$.get('https://openapi.naver.com/v1/nid/me', 
							{
								Authorization : 'Bearer ' + access_token,
							},
							function(data_var){
									//결과가 없을시 에러처리
									if(data_var.result == ""){
											return false;
									}else{
										//에러일경우 에러처리
										if(data_var.result == "error"){
											if(data_var.msg) alert(data_var.msg);
											return false;
										}else if(data_var.result == "success"){
											if(data_var.url){location.href=data_var.url;}
											if(data_var.msg) alert(data_var.msg);
											return true;
										}else{
											alert("잘못된 접근입니다.");
											return false;
										}
									}
							},'json');
					});
			} else {
					//Callback으로 전달된 데이터가 정상적이지 않을 경우에 대한 처리
					return ;
			}
	}

	<?if($_GET[start]){?>loginNaver();<?}?>
</script>
<?
include "../inc/_tail.php";
?>