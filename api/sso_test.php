<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" Content="IE=edge" />
<title>Purina Pet Care SSO TEST</title>
<script type='text/javascript' src='https://testmembers.lpoint.com:500/api/js/jquery-1.12.3.min.js'></script>
<script type='text/javascript' src='https://testmembers.lpoint.com:500/api/js/serialize.object.js'></script>
<script type='text/javascript' src='https://testmembers.lpoint.com:500/api/js/json2.js'></script>
<script type='text/javascript' src='https://testmembers.lpoint.com:500/api/js/lotte.sso.api.js'></script>
<script>
<?
    $urlParam['acesTkn'] = isset($_POST['acesTkn']) ? "&acesTkn={$_POST['acesTkn']}" : "";
?>
var sso = null;
$(function() {
    $.ajax({
        url: "http://13.209.136.29:8080/Sso/?type=initialize<?=$urlParam['acesTkn']?>",
        type: "GET",
        cache:false,
        dataType:"json"})
    .done(function(data) {
        console.log(data);
        if(data.result_code === '0') {
            initialize(data);
        }
    })
    .fail(function(data) {
        console.error(data);
    });
});

/** **/
function initialize(data) {
	var CCO_SITE_NO = data.CCO_SITE_NO;
    var clntAkInf = data.clntAkInf;
        $("#clntAkInf").text(clntAkInf);
        sso = new SsoClientLibrary({
        ccoSiteNo: CCO_SITE_NO,                 // 제휴사사이트번호
        clntAkInf: clntAkInf,            // 클라이언트요청정보
        urEvnmtDc : '0',                        // 사용자환경구분코드
        vrblNm : 'sso',                         // 라이브러리변수명
        srnOpt : {
            opMd : '0',                         // 오픈모드
            srnHeaderExpsYn: 'Y'                // 화면헤더노출여부
        } 
    });   
}

/** API 호출 **/
function getSsoToken() {
    sso.getSsoTkn({
        callback: function(ssoTkn) {
            console.log(JSON.stringify(ssoTkn));
        }
    });
}

function login() {
    sso.callLogin({ akUrl: '/exBiz/login/login_01_001', // 요청URL
        akDta: { onlId: $('#onid').val(), cstPswd: $('#onpw').val()}, // 요청데이터
        aftPcMd : '0',                                  // 후처리모드
        rturUrl: window.location.href,                  // 리턴URL
        rturUrlCaloMethd: 'GET',                        // 리턴URL호출메소드
        callback : function(rspDta) {                  // 콜백함수
            /** 7. 일반로그인 후처리 */
            if ('00' == rspDta.rspC) {
                /** 7.1. 추후 자동로그인을 위해 클라이언트에 갱신토큰 저장 (선택) */
                localStorage.setItem('rnwTkn', rspDta.rnwTkn);
                /** 7.2. 로그인 후처리를 위해 접근토큰 전달 (필수) */
                $('#acesTkn').val(rspDta.acesTkn);
                //$('form').submit();
            }else{
                // 로그인 실패
                $('#onlId').val('');
                $('#password').val('');
            }
        }
    });
}


/* 로그인한 사용자의 정보를 가져온다. */
function getUserInfo() {
    $.ajax({
        url: "http://13.209.136.29:8080/Sso/?type=urInfInq_01_002<?=$urlParam['acesTkn']?>",
        type: "GET",
        cache:false,
        dataType:"json"})
    .done(function(data) {
        console.log(data);
    })
    .fail(function(data) {
        console.error(data);
    });
}

function link1(){
    sso.callScreen({
        akUrl: '/exView/join/mbrJoin_01_001',   // 요청URL
        rturUrl: window.location.href,          // 제휴사리턴URL
        rturUrlCaloMethd: 'GET'                 // 제휴사리턴URL호출메소드
    });
}

function link2(){
    sso.callScreen({
        akUrl: '/view/manage/mbrManage_01_000',   // 요청URL
        rturUrl: window.location.href,          // 제휴사리턴URL
        rturUrlCaloMethd: 'GET'                 // 제휴사리턴URL호출메소드
    });
}

function link3(){
    sso.callScreen({
        akUrl: '/view/manage/chPassword_01_000',   // 요청URL
        rturUrl: window.location.href,          // 제휴사리턴URL
        rturUrlCaloMethd: 'GET'                 // 제휴사리턴URL호출메소드
    });
}

function link4(){
    sso.callScreen({
        akUrl: '/exView/manage/fdPassword_01_001',   // 요청URL
        rturUrl: window.location.href,          // 제휴사리턴URL
        rturUrlCaloMethd: 'GET'                 // 제휴사리턴URL호출메소드
    });
}

function link5(){
    sso.callScreen({
        akUrl: '/exView/manage/fdId_01_001',   // 요청URL
        rturUrl: window.location.href,          // 제휴사리턴URL
        rturUrlCaloMethd: 'GET'                 // 제휴사리턴URL호출메소드
    });
}

function link6(){
    sso.callScreen({
        akUrl: '/view/manage/mbrSes_01_001',   // 요청URL
        rturUrl: window.location.href,          // 제휴사리턴URL
        rturUrlCaloMethd: 'GET'                 // 제휴사리턴URL호출메소드
    });
}

function link7(){
    sso.callScreen({
        akUrl: '/view/login/login_04_001',   // 요청URL
        rturUrl: window.location.href,          // 제휴사리턴URL
        rturUrlCaloMethd: 'GET'                 // 제휴사리턴URL호출메소드
    });
}

</script>
</head>
<body style="font-family: monospace;">
clntAkInf : <span id="clntAkInf"></span><br />
acesTkn : <span><?=$_POST['acesTkn']?></span><br />
<hr />
<input type="button" value="SSO TOKEN TEST" onclick="getSsoToken();" />
<hr />
<button type="button" onclick="link1();"> 회원가입</button>
<button type="button" onclick="link2();"> 회원정보 변경</button>
<button type="button" onclick="link3();"> 비밀번호 변경</button>
<button type="button" onclick="link4();"> 비밀번호 찾기</button>
<button type="button" onclick="link5();"> 아이디 찾기</button>
<button type="button" onclick="link6();"> 회원탈퇴</button>
<button type="button" onclick="link7();"> 비밀번호 변경 캠페인</button>
<hr />
ID: <input type="text" id="onid" value="" /><br />
PW: <input type="password" id="onpw" value="" /> <br />
<input type="button" value="SSO LOGIN TEST" onclick="login();" />
<hr />
<input type="button" value="SSO INFO TEST" onclick="getUserInfo();" />
<form action="sso_test.php" method="post"><input type="hidden" name="acesTkn" id="acesTkn"></form>
</body>
</html>