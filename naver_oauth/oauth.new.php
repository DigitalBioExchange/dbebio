<?php
$menu_num = 0;
include "../inc/_common.php";
include "../inc/_head.php";
?>

<script src="https://static.nid.naver.com/js/naverLogin.js"></script>
// jquery.cookie.js가 필요합니다. https://plugins.jquery.com/cookie/ 가서 다운받아 추가시켜줍니다.


<script type="text/javascript">
 //아래 정보는 개발자 센터에서 애플리케이션 등록을 통해 발급 받을 수 있습니다. 
 var naver = NaverAuthorize({
	client_id : "p38xqswd6S0pCNKzEZoZ",
	redirect_uri : "http://www.whattong.com/naver_oauth/oauth.php",
	client_secret : "73XTXvIkkT"
 });

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

 function login_clk(){
  var state = generateState();
  saveState(state);
  naver.login(state); // 로그인 !!
/*  js파일의 login을 보니 ,
document.location.href = URL.LOGIN + "?client_id=" + client_id + "&response_type=code&redirect_uri=" + encodeURIComponent(redirect_uri) + "&state=" + state_token+"&svctype=0"; location.href 로 때려버리네요 .. 비동기식이였으면 참 좋았을텐데..
마지막의 svctype=0은 IE를 이용할 경우 로그인 시 자동으로 리사이징이 될때가 있는데, 그걸 막아주는 역할을 합니다.
*/
 }

 window.onload=function(){
        checkLoginState(); // 페이지가 로딩될때 자동으로 실행!!
    }

 var tokenInfo = { access_token:"" , refresh_token:"" };
 var session_chk = '<%=Session("SNS_COMMENT_TYPE")%>';
 var state = $.cookie("state_token");
 var access_session = '<%=Session("access_tok")%>';
 var refresh_session = '<%=Session("refresh_tok")%>';

 function checkLoginState() {
  if(naver.checkAuthorizeState(state) == "connected") {
  /*  로그인 후 redirect된 페이지의 url에 &code=~~~~&state=~~~~가 추가된것을 볼 수 있습니다.
 checkAuthorizeState(state)  는 URL에서 그 code와 state값을 잘라서 가져온 값이 true일때 connected를 리턴시켜주는데요. 여기서 URL에 붙는 code와 state값은 로그인 한 그 페이지에만 적용되기때문에 로그인 상태를 유지하기 위해서는 session 또는 cookie를 사용하여 처리해주어야 합니다.
 */

   naver.getAccessToken(function(data) {
// 코드가 어떻게 돌아가는지 설명하기에는 너무 많기도 많고... 귀찮다.. 
    var response = data._response.responseJSON;
    if(response.error === "fail") {
     return ;
    }
    if(response.access_token != undefined && response.refresh_token != undefined){
//위에서 생성한 변수(tokenInfo.access_token 외 1개)에 getAccessToken을 통해 받아온 response값들을 넣어줌
     tokenInfo.access_token = response.access_token;
     tokenInfo.refresh_token = response.refresh_token;
    }else if(access_session != "" && refresh_session != ""){
     tokenInfo.access_token = access_session; 
     tokenInfo.refresh_token = refresh_session;
/* 전 새로고침할때 로그인 상태를 유지하기위해 session값을 사용했습니다. 그럼 처음 로그인시
response.access_token; 을 세션에 넣어주는 부분도 있어야겠죠??
*/
    }else{
     return;
    }
    id_check();
   });
  }else if(chk_code == "TRUE" && chk_token == "TRUE"){
  // 다른페이지로 이동했을 때, 로그인 상태를 유지하기위해 (처음 로그인 후 입니다)
    if(session_chk == "NAVER"){
 // 여러가지 SNS가 있었기때문에 session_chk라는 세션변수로 sns를 구별했습니다.
     $.post("/oAuthASPExample/session_check.asp", {}, function (responseasp) {
// 처음 로그인 했을때, session에 프로필 정보를 저장하는데, 그것의 값이 유효한지 확인합니다.
      if(responseasp == "Y"){
// 유효할 경우.
       var image = document.getElementById('facebookimage');
       var name = document.getElementById('navername');
       var id = document.getElementById('naverid');
       image.src =  '<%=Session("SNS_PHOTO")%>';
       name.innerHTML = '<%=Session("SNS_UNAME")%>';
       id.innerHTML = '<%=Session("SNS_ID")%>'; 
       getCommentList("");
       document.getElementById('sign_in_container').style.display = "none";
       document.getElementById('sign_out_container').style.display = "";
       document.getElementById('naver_submit').style.display = "";
       document.getElementById('snslogininfoN').style.display = "";
       document.getElementById('snsTopImg').src = '/_common/img/ico_reply04.jpg';
       document.getElementById('snsTopImg').alt = 'naver';
      }else{
// 아닐경우 
        return ;
      }
     });
    }
  }else{
   return ;
  }
 }
 function id_check(){
// 처음 로그인 했을 경우, chekcLoginState()에서 이곳을 호출합니다.
  var URL = "https://apis.naver.com/nidlogin/nid/getUserProfile.json?response_type=json";
  naver.api(URL, tokenInfo.access_token, function(data) {
  var response = data._response.responseJSON;
  
  response.resultcode; //: API호출 결과
  response.message;    //: API호출 결과 메시지 
  response.response;    //: API호출 결과 데이터(사용자정보)
  var n_name = response.response.name; // 사용자 이름
  var n_id_discern = response.response.id; //: 사용자 정보 사용자 식별값
  var n_profile_image = response.response.profile_image;  // : 사용자 정보 프로필이미지URL
  var linkUrl = "";
  var image = document.getElementById('facebookimage');
  var name = document.getElementById('navername');
  var id = document.getElementById('naverid');

// 밑에는 정보들을 세션에 넣어주기위함
  $.post("/oAuthASPExample/comment_session_process_n.asp", { "userid": n_id_discern, "username": n_name, "naaccesstoken": tokenInfo.access_token, "picture":n_profile_image, "link":linkUrl, "access_session" : tokenInfo.access_token, "refresh_session": tokenInfo.refresh_token },  
  function (responseasp) {  
   if(responseasp == "N"){
    alert("로그인 세션 실패");           
   }else{ 
    image.src =  n_profile_image;
    name.innerHTML = n_name;
    id.innerHTML = n_id_discern;
    getCommentList("");
    document.getElementById('sign_in_container').style.display = "none";
    document.getElementById('sign_out_container').style.display = "";
    document.getElementById('naver_submit').style.display = "";
    document.getElementById('snslogininfoN').style.display = "";
    document.getElementById('snsTopImg').src = '/_common/img/ico_reply04.jpg';
    document.getElementById('snsTopImg').alt = 'naver';
    }
   });
  });
 }

 /* 네이버 로그아웃 */
 function naver_logout(){
  var URL = 'http://'+'<%=request.servervariables("HTTP_HOST") & request.servervariables("HTTP_url") %>';
//음.. 다 구현 후 로그아웃 버튼을 눌렀을 때, 로그인과 같이 url에 code와 state값이 붙습니다. 근데 이 code와 state값 때문에 로그아웃 후, 바로 로그인할 경우 에러가 뜹니다.. 쓰잘데기없음으로 지워줍시다.
  var s_index = URL.indexOf("code=");
// js 기존 파일일 경우 code가 state앞에 있습니다.
  if(s_index != -1){
// indexOf한 값이 있을 경우.
   URL = URL.substring(0,s_index-1);
  }
  naver.logout('<%=Session("SNS_TOKEN")%>', function(){
// naverLogin.js에서 지원하는 로그아웃
   $.post("/oAuthASPExample/naver_out.asp", {},  function (responseasp) {
// 내가 생성한 session값들 없애주기
    if(responseasp!="Y"){
     alert("Error");
     //location.replace('/unmember/memberrege?flag=1');            
    }else{
     session_chk = "";
     $.removeCookie("state_token");
     location.href = URL;
    }
   });
  });
 }
 </script>
  <!-- 네이버 로그인 끝 -->
<?
include "../inc/_tail.php";
?>