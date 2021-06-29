<?
// register_globals 가 off 일때 사용
function parse_query_str($level=0) {
	# php.ini 의 register_globals 값을 확인한다.
	# register_globals = OFF 일 경우만 작동.
	if(ini_get("register_globals")) return;

	# php 버젼이 4.1 보다 낮을 경우에는 함수 안에서 trackvar
	# 를 사용하기 위해 global 로 지정해 줘야 한다.
	if(substr(PHP_VERSION,0,3) < 4.1) {
		global $_GET, $_POST;
		if($level)
		global $_COOKIE,$_SESSION,$_SERVER;
	}

	# 4.1 부터는 trackvars 에 대해 무조건 배열로 생성을 하기
	# 때문에 is_array() 함수 보다는 count() 함수로 배열의 수를
	# 체크한다.
	if(custom_count($_GET)) {
		foreach($_GET as $key => $value) {
			global ${$key};
			${$key} = $value;
		}
	}

	if(custom_count($_POST)) {
		foreach($_POST as $key => $value) {
			global ${$key};
			${$key} = $value;
		}
	}

	if( $level && custom_count($_COOKIE) ) {
		foreach($_COOKIE as $key => $value) {
			global ${$key};
			${$key} = $value;
		}
	}

	if( $level && custom_count($_SESSION) ) {
		foreach($_SESSION as $key => $value) {
			global ${$key};
			${$key} = $value;
		}
	}

	if( $level && custom_count($_SERVER) ) {
		foreach($_SERVER as $key => $value) {
			global ${$key};
			${$key} = $value;
		}
	}
}

//디버깅용 print_r
function p_arr($arr){
	if(is_array($arr)){
		echo "<pre>";
		print_r($arr);
		echo "</pre>";
	}else{
		echo "배열아님 : $arr </br>";
	}
}

//디버깅용 출력
function test_echo($var)
{
	global $_cfg;
	if(in_array($_SERVER['REMOTE_ADDR'], $_cfg['supersuper_ip'])){
		p_arr($var);
	}
}

//아작스용 에러출력
function ajax_error_print($msg)
{
	$arr = array();
	$arr['result'] = "error";
	$arr['msg'] = $msg;
	echo json_encode($arr);exit;
}

//어플 location.replace 오작동에 대해 처리한 부분
function goto_url($url)
{
	global $user_agent, $org_user_agent;
    echo "<script language='JavaScript'>";
	if($org_user_agent == "mobile" && $user_agent == "app"){
	echo "if (!!(window.history && history.replaceState)) {";
	echo "window.history.replaceState({}, document.title, '$url');";
	echo "}";
	}
	echo "location.replace('$url');";
	echo "</script>";
    exit;
}

function goto_url_frame($url)
{
    echo "<script language='JavaScript'> parent.location.replace('$url'); </script>";
    exit;
}

function alert($msg='', $url='')
{
	global $_lang, $lang_code, $header_printed, $_is_ajax;
	if($_is_ajax){
		if (!$msg) $msg = "{__lang['lib']['text_001']}";
		$arr = array();
		$arr['result'] = "error";
		$arr['msg'] = $msg;
		$arr['url_togo'] = $url;
		echo json_encode($arr);exit;
		exit;
	}else{
		if (!$msg) $msg = "{__lang['lib']['text_001']}";

		if($header_printed != '1') header("Content-type: text/html; charset=UTF-8");

		echo "<script language='javascript'>alert('$msg');";
		if (!$url)
			echo "history.go(-1);";
		echo "</script>";
		if ($url)
			// 4.06.00 : 불여우의 경우 아래의 코드를 제대로 인식하지 못함
			//echo "<meta http-equiv='refresh' content='0;url=$url'>";
			goto_url($url);
		exit;
	}
}


function alert_frame($msg='', $url='')
{
	global $_lang, $lang_code, $header_printed;
    if (!$msg) $msg = "올바른 사용법이 아닙니다.";
	if($header_printed != '1') header("Content-type: text/html; charset=UTF-8");

	echo "<script language='javascript'>alert('$msg');";
    if (!$url)
		echo "parent.history.go(-1);";
    echo "</script>";
    if ($url)
        // 4.06.00 : 불여우의 경우 아래의 코드를 제대로 인식하지 못함
        //echo "<meta http-equiv='refresh' content='0;url=$url'>";
        goto_url_frame($url);
    exit;
}

function alert1($msg='')
{
	global $_lang, $lang_code, $header_printed;
    if (!$msg) $msg = "올바른 사용법이 아닙니다.";
	if($header_printed != '1') header("Content-type: text/html; charset=UTF-8");
	echo "<script language='javascript'>alert('$msg');";
    echo "</script>";
}

function alert_close($msg='')
{
	global $_lang, $lang_code, $header_printed;
    if (!$msg) $msg = "올바른 사용법이 아닙니다.";
	if($header_printed != '1') header("Content-type: text/html; charset=UTF-8");
	echo "<script language='javascript'>alert('$msg');";
	echo "self.close();";
    echo "</script>";
	exit;
}

function make_get($get, $not_include = ''){
	$get_var = array();
	$not_arr = explode(",", $not_include);
	foreach ($get as $k => $v) {
		if(!in_array($k, $not_arr) && $v != ""){
			$get_var[] = "$k=".urlencode($v);
		}
	}

	$get_string = implode("&", $get_var);
	return $get_string;
}

// 마이크로 타임을 얻어 계산 형식으로 만듦
function get_microtime()
{
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
}

// 로그인 페이지로 보내기 : 
//자바스크립트꺼는 권한있는 페이지 넘어가기 전 - 로그인 후 넘어가기전 페이지로 돌아옴
//이거는 권한있는 페이지 넘어가서 넘기는것 - 로그인 후 가려는 페이지로 이동함
function goto_login()
{
	global $_cfg, $is_sub, $_lang;
	if(!$_SESSION['ss_mb_idx']){
		alert("{__lang['lib']['text_002']}", "/member/login.php");
		exit;
	}
}

function goto_login_frame()
{
	global $_cfg, $is_sub;
	if(!$_SESSION['ss_mb_idx']){
		alert_frame("로그인이 필요한 서비스 입니다.", "/member/login.php");
		exit;
	}
}


function goto_login2()
{
	global $_cfg, $is_sub, $_lang;
	if(!$_SESSION['ss_mb_idx'] && !$_SESSION['ss_od_tel']){
		alert("{__lang['lib']['text_002']}", "/member/login2.php");
		exit;
	}
}

function already_logged()
{
	global $_lang, $lang_code;
	if($_SESSION['ss_mb_idx']){
		alert("{__lang['lib']['text_003']}", "/");
		exit;
	}
}

// 파일 읽어서 변수로 내용 저장하기
function file_read($file)
{
	global $_cfg;

	$handle = fopen($file, "r");
	$contents = fread($handle, filesize($file));
	fclose($handle);
	return $contents;
}

// 글자 자르기
function cutStr($str, $len, $suffix="…")
{
    $s = mb_substr($str, 0, $len, 'utf-8');

    if (strlen($s) >= strlen($str))
        $suffix = "";
    return $s . $suffix;
}

// 썸네일 만들기
function put_gdimage($img_name, $width, $height, $save_name, $ratio = 0){

	global $_cfg, $_lang;

	@unlink($save_name);
	// GD 버젼체크
	$gd = gd_info();
	$gdver = substr(preg_replace("/[^0-9]/", "", $gd['GD Version']), 0, 1);
	if(!$gdver) return "GD 버젼체크 실패거나 GD 버젼이 1 미만입니다.";

	$srcname = $img_name;
	$filesize =filesize($srcname);
	//if($filesize > 500000) return 0;
	$timg = @getimagesize($srcname);
	if($timg[2] != 1 && $timg[2] != 2 && $timg[2] != 3) return 0;
	//if($timg[2] != 1 && $timg[2] != 2 && $timg[2] != 3 && $timg[2] != 15) return "확장자가 jp(e)g/gif/png/bmp 가 아닙니다.";

	if($ratio == 0){
		if($height == 0){
			$rate = $timg[1]/$timg[0];
			$height = round($width * $rate);
		}
	}else{
		if($height == 0){
			$rate = $timg[1]/$timg[0];
			$height = round($width * $rate);
		}else{
			$o_w = $width;
			$o_h = $height;

			$o_rate = $width/$height;
			$i_rate = $timg[0]/$timg[1];
			
			// 세로가 클때
			if($o_rate > $i_rate){
				$height = $o_h;
				$width = round($height * $i_rate);
				$rslt = "height";
			}else{
				$rslt = "width";
				$width = $o_w;
				$height = round($width / $i_rate);
			}
		}
	}

	if($timg[2] == 1) $cfile = imagecreatefromgif($img_name);
	else if($timg[2] == 2) $cfile = imagecreatefromjpeg($img_name);
	else if($timg[2] == 3) $cfile = imagecreatefrompng($img_name);


	$dest = imagecreatetruecolor($width, $height);

	imagecopyresampled($dest, $cfile, 0, 0, 0, 0, $width, $height, $timg[0], $timg[1]);

	if($timg[2] == 1) imagegif($dest, $save_name, 90);    // 1~100
	else if($timg[2] == 2) imagejpeg($dest, $save_name, 90); // 1~100
	else if($timg[2] == 3) imagepng($dest, $save_name, 9);  //  1~9

	@chmod($save_name, 0666);
	imagedestroy($dest);
	return $rslt;
}

function Chk_exif_WH($src, $tgt)
{

	global $_cfg, $_lang;

	@move_uploaded_file($src, $tgt);
	@chmod($tgt, 0666);
	return 0;

	// GD 버젼체크
	$gd = gd_info();
	$gdver = substr(preg_replace("/[^0-9]/", "", $gd['GD Version']), 0, 1);
	if(!$gdver) return "GD 버젼체크 실패거나 GD 버젼이 1 미만입니다.";


	$timg = @getimagesize($src);
	if(($timg[2] != 1 && $timg[2] != 2 && $timg[2] != 3)){
		@move_uploaded_file($src, $tgt);
		@chmod($tgt, 0666);
		return 0;
	}else{

		$exif = @exif_read_data($src);

		if(!empty($exif['Orientation'])) {

		
			if($timg[2] == 1) $cfile = imagecreatefromgif($src);
			else if($timg[2] == 2) $cfile = imagecreatefromjpeg($src);
			else if($timg[2] == 3) $cfile = imagecreatefrompng($src);

		

		
			switch($exif['Orientation']) {
				case 8:
					$cfile = imagerotate($cfile,90,0);
					break;
				case 3:
					$cfile = imagerotate($cfile,180,0);
					break;
				case 6:
					$cfile = imagerotate($cfile,-90,0);
					break;
			}
			if($timg[2] == 1) imagegif($cfile, $tgt, 100);
			else if($timg[2] == 2) imagejpeg($cfile, $tgt, 100);
			else if($timg[2] == 3) imagepng($cfile, $tgt, 9);

			

			chmod($tgt, 0666);
			imagedestroy($cfile);

		}else{
			@move_uploaded_file($src, $tgt);
			@chmod($tgt, 0666);
			return 0;
		}
	}
}

function Chk_exif_WH2($src, $tgt)
{
	global $_cfg, $_lang;
	
	// GD 버젼체크
	$gd = gd_info();
	$gdver = substr(preg_replace("/[^0-9]/", "", $gd['GD Version']), 0, 1);
	if(!$gdver) return "GD 버젼체크 실패거나 GD 버젼이 1 미만입니다.";


	$timg = @getimagesize($src);
	if(($timg[2] != 1 && $timg[2] != 2 && $timg[2] != 3)){

		@copy($src, $tgt);
		@unlink($src);
		@chmod($tgt, 0666);
		return 0;
	}else{

		$exif = @exif_read_data($src);

		if(!empty($exif['Orientation'])) {

		
			if($timg[2] == 1) $cfile = imagecreatefromgif($src);
			else if($timg[2] == 2) $cfile = imagecreatefromjpeg($src);
			else if($timg[2] == 3) $cfile = imagecreatefrompng($src);

		

		
			switch($exif['Orientation']) {
				case 8:
					$cfile = imagerotate($cfile,90,0);
					break;
				case 3:
					$cfile = imagerotate($cfile,180,0);
					break;
				case 6:
					$cfile = imagerotate($cfile,-90,0);
					break;
			}
			if($timg[2] == 1) imagegif($cfile, $tgt, 100);
			else if($timg[2] == 2) imagejpeg($cfile, $tgt, 100);
			else if($timg[2] == 3) imagepng($cfile, $tgt, 9);

			

			@chmod($tgt, 0666);
			imagedestroy($cfile);
			@unlink($src);

		}else{
			@copy($src, $tgt);
			@unlink($src);
			@chmod($tgt, 0666);
			return 0;
		}
	}
}

//주민번호 체크 함수
function chk_jumin($param1)
{
	$num = substr($juminno, 6, 1);
	if($num >= 5){
		$rslt = check_fgnno($param1);
	}else{
		$rslt = chk_jumin2($param1);
	}
	return $rslt;
}
function chk_jumin2($param1)
{
	$juminno = $param1;

	$sum_1 = 0;
	$sum_2 = 0;
	$at = 0;

	$sum_1 = (substr($juminno, 0, 1)*2)+
			(substr($juminno, 1, 1)*3)+
			(substr($juminno, 2, 1)*4)+
			(substr($juminno, 3, 1)*5)+
			(substr($juminno, 4, 1)*6)+
			(substr($juminno, 5, 1)*7)+
			(substr($juminno, 6, 1)*8)+
			(substr($juminno, 7, 1)*9)+
			(substr($juminno, 8, 1)*2)+
			(substr($juminno, 9, 1)*3)+
			(substr($juminno, 10, 1)*4)+
			(substr($juminno, 11, 1)*5);
	$sum_2=$sum_1 % 11;

	if ($sum_2 == 0) 
		$at = 10;
	else 
	{
		if ($sum_2 == 1) 
			$at = 11;
		else 
			$at = $sum_2;
	}
	$att = 11 - $at;
	// 1800 년대에 태어나신 분들은 남자, 여자의 구분이 9, 0 이라는 
	// 얘기를 들은적이 있는데 그렇다면 아래의 구문은 오류이다.
	// 하지만... 100살넘은 분들이 주민등록번호를 과연 입력해볼까?
	if (substr($juminno, 12, 1) != $att || 
		substr($juminno, 2,2) < '01' ||
		substr($juminno, 2,2) > '12' ||
		substr($juminno, 4,2) < '01' ||
		substr($juminno, 4,2) > '31' ||
		substr($juminno, 6,1) > 4) 
	{
	   return false;
	}
	return true;
}

function check_fgnno($param1) {
        $sum=0;
        $odd=0;
        $buf = array();
        for($i=0; $i<13; $i++) {
			$buf[$i]= substr($juminno, $i, 1); 
		}
        
		$odd = $buf[7]*10 + $buf[8];

        if($odd % 2 != 0) { return false; }
        if( ($buf[11]!=6) && ($buf[11]!=7) && ($buf[11]!=8) && ($buf[11]!=9) ) {
                return false;
        }
        $multipliers = array(2,3,4,5,6,7,8,9,2,3,4,5);

        for($i=0, $sum=0; $i<12; $i++) { $sum += ($buf[$i] *= $multipliers[$i]); }
        $sum = 11 - ($sum%11);
        if($sum >= 10) { $sum -= 10; }
        $sum += 2;
        if($sum >= 10) { $sum -= 10; }
        if($sum != $buf[12]) { return false; }
        return true;
}


// 사업자번호 체크 함수
function chk_saup($saup)
{
   if (!trim($saup)) return false; 

	$sum = 0;
	$at = 0;
	$att = 0;
	$saupjano= $saup;
	$sum = (substr($saupjano, 0, 1)*1)+
		  (substr($saupjano, 1, 1)*3)+
		  (substr($saupjano, 2, 1)*7)+
		  (substr($saupjano, 3, 1)*1)+
		  (substr($saupjano, 4, 1)*3)+
		  (substr($saupjano, 5, 1)*7)+
		  (substr($saupjano, 6, 1)*1)+
		  (substr($saupjano, 7, 1)*3)+
		  (substr($saupjano, 8, 1)*5);
	$sum += (substr($saupjano, 8, 1)*5)/10;
	$at = $sum % 10;
	if ($at != 0) 
		$att = 10 - $at;  

	if (substr($saupjano, 9, 1) != $att) 
	{
	   return false;
	}

	return true;
}


// utf-8 을 euc-kr로 변경
function utf_to_euc($arr)
{
	if(is_array($arr)){
		foreach($arr as $k => $v){
			$arr[$k] = iconv("utf-8", "euc-kr", $v);
		}
	}else{
		$arr = iconv("utf-8", "euc-kr", $arr);
	}

	return $arr;
}

// url에 http:// 를 붙인다
function set_http($url)
{
    if (!trim($url)) return;

    if (!preg_match("/^(http|https|ftp|telnet|news|mms):\/\//i", $url))
        $url = "http://" . $url;

    return $url;
}

function set_http2($url)
{
    if (!trim($url)) return;

    if (!preg_match("/^(http|https|ftp|telnet|news|mms):\/\//i", $url) && substr($url, 0, 1) != "/")
        $url = "http://" . $url;

    return $url;
}

function url_auto_link($str, $target = "")
{
    global $config;

    // 속도 향상 031011
    $str = preg_replace("/&lt;/", "\t_lt_\t", $str);
    $str = preg_replace("/&gt;/", "\t_gt_\t", $str);
    $str = preg_replace("/&amp;/", "&", $str);
    $str = preg_replace("/&quot;/", "\"", $str);
    $str = preg_replace("/&nbsp;/", "\t_nbsp_\t", $str);
    $str = preg_replace("/([^(http:\/\/)]|\(|^)(www\.[^[:space:]]+)/i", "\\1<A HREF=\"http://\\2\" TARGET='$target'>\\2</A>", $str);
    $str = preg_replace("/([^(HREF=\"?'?)|(SRC=\"?'?)]|\(|^)((http|https|ftp|telnet|news|mms):\/\/[a-zA-Z0-9\.-]+\.[\xA1-\xFEa-zA-Z0-9\.:&#=_\?\/~\+%@;\-\|\,]+)/i", "\\1<A HREF=\"\\2\" TARGET='$target'>\\2</A>", $str);
    // 이메일 정규표현식 수정 061004
    //$str = preg_replace("/(([a-z0-9_]|\-|\.)+@([^[:space:]]*)([[:alnum:]-]))/i", "<a href='mailto:\\1'>\\1</a>", $str);
    $str = preg_replace("/([0-9a-z]([-_\.]?[0-9a-z])*@[0-9a-z]([-_\.]?[0-9a-z])*\.[a-z]{2,4})/i", "<a href='mailto:\\1'>\\1</a>", $str);
    $str = preg_replace("/\t_nbsp_\t/", "&nbsp;" , $str);
    $str = preg_replace("/\t_lt_\t/", "&lt;", $str);
    $str = preg_replace("/\t_gt_\t/", "&gt;", $str);

    return $str;
}

// 악성태그 변환
function bad_tag_convert($code) 
{
    return preg_replace("/\<([\/]?)(script|iframe)([^\>]*)\>/i", "&lt;$1$2$3&gt;", $code);
}

// HTML SYMBOL 변환
// &nbsp; &amp; &middot; 등을 정상으로 출력
function html_symbol($str)
{
    return preg_replace("/\&([a-z0-9]{1,20}|\#[0-9]{0,3});/i", "&#038;\\1;", $str);
}

function get_text_arr($arr, $field, $html=0)
{
	$rslt = array();
	foreach($arr as $k => $v){
		$v[$field] = get_text($v[$field], $html);
		$rslt[] = $v;
	}

	return $rslt;
}


// TEXT 형식으로 변환
function get_text($str, $html=0)
{
    /* 3.22 막음 (HTML 체크 줄바꿈시 출력 오류때문)
    $source[] = "/  /";
    $target[] = " &nbsp;";
    */

    // 3.31
    // TEXT 출력일 경우 &amp; &nbsp; 등의 코드를 정상으로 출력해 주기 위함
    if ($html == 0) {
        $str = html_symbol($str);
    }

    $source[] = "/</";
    $target[] = "&lt;";
    $source[] = "/>/";
    $target[] = "&gt;";
    //$source[] = "/\"/";
    //$target[] = "&#034;";
    $source[] = "/\'/";
    $target[] = "&#039;";
    $source[] = "/\"/";
    $target[] = "&quot;";
    //$source[] = "/}/"; $target[] = "&#125;";
    if ($html) {
        $source[] = "/\n/";
        $target[] = "<br/>";
		$source[] = "/\r/";
        $target[] = "";
    }

    return preg_replace($source, $target, $str);
}

function conv_content_arr($arr, $field, $html=0)
{
	$rslt = array();
	foreach($arr as $k => $v){
		$v[$field] = conv_content($v[$field], $html);
		$rslt[] = $v;
	}

	return $rslt;
}

// 내용을 변환
function conv_content($content, $html)
{
	global $_cfg;
    if ($html)
    {
        $source = array();
        $target = array();

        $source[] = "//";
        $target[] = "";

        if ($html == 2) { // 자동 줄바꿈
            $source[] = "/\n/";
            $target[] = "<br/>";
        }


        // 테이블 태그의 갯수를 세어 테이블이 깨지지 않도록 한다.
        $table_begin_count = substr_count(strtolower($content), "<table");
        $table_end_count = substr_count(strtolower($content), "</table");
        for ($i=$table_end_count; $i<$table_begin_count; $i++)
        {
            $content .= "</table>";
        }

        $content = preg_replace($source, $target, $content);
        $content = bad_tag_convert($content);

        $content = preg_replace("/(on)([a-z]+)([^a-z]*)(\=)/i", "&#111;&#110;$2$3$4", $content);
        $content = preg_replace("/(dy)(nsrc)/i", "&#100;&#121;$2", $content);
        $content = preg_replace("/(lo)(wsrc)/i", "&#108;&#111;$2", $content);
        $content = preg_replace("/(sc)(ript)/i", "&#115;&#99;$2", $content);
        $content = preg_replace("/(ex)(pression)/i", "&#101&#120;$2", $content);

		if($user_agent == "app"){
			$pattern = "/<a[^>]*href=[\"']?([^>\"']+)[\"']?[^>]*>/i";
			preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);
			for($i=0;$i<custom_count($matches);$i++){
				$a_links_tag = $matches[$i][0];
				$a_links = $matches[$i][1];
				$a_links_tag2 = str_replace($a_links, "javascript:app_window_open_browser('', '".$a_links."');", $a_links_tag);

				$content = str_replace($a_links_tag, $a_links_tag2, $content);
			}
		}


		if($_cfg['editor_dir'] == "/froala_editor"){
			$content = '<div class="froala-view">'.$content.'</div>';
			
		}
    }
    else // text 이면
    {
        // & 처리 : &amp; &nbsp; 등의 코드를 정상 출력함
        $content = html_symbol($content);

        // 공백 처리
		//$content = preg_replace("/  /", "&nbsp; ", $content);
		$content = str_replace("  ", "&nbsp; ", $content);
		$content = str_replace("\n ", "\n&nbsp;", $content);

        $content = get_text($content, 1);

        //$content = url_auto_link($content);
    }

    return $content;
}

// 세션변수 생성
function set_session($session_name, $value)
{
    //session_register($session_name);
    // PHP 버전별 차이를 없애기 위한 방법
    $$session_name = $_SESSION["$session_name"] = $value;
}


// 세션변수값 얻음
function get_session($session_name)
{
    return $_SESSION[$session_name];
}


// 쿠키변수 생성
function set_cookie($cookie_name, $value, $expire)
{
    setcookie(md5($cookie_name), base64_encode($value), time() + $expire, '/');
}


// 쿠키변수값 얻음
function get_cookie($cookie_name)
{
    return base64_decode($_COOKIE[md5($cookie_name)]);
}



// 자리수만큼 0 앞에 붙이기
function make_zero_first($num, $arg)
{
	$plus = "1";
	for($i=0;$i<$arg;$i++){
		$plus .= "0";
	}

	$tmp = $num + $plus;
	$result = substr($tmp, (0 - $arg));
	return $result;
}

function get_nemeric($arg)
{
	$tmp = preg_replace("/[^0-9]/", '', $arg);
	if($tmp == "") $tmp = 0;
	return $tmp;
}

function get_int_to_won($value)
{
	$len_value=strlen($value);

	$down_cate=array("","십","백","천");
	$num_to_won=array("","일","이","삼","사","오","육","칠","팔","구");

	if($len_value > 13)
	{
		return "1조 미만만 표현 가능합니다.";
	}
	else
	{
		$sub_value[0]=substr($value,-4);
		$sub_value[1]=substr($value,-8,-4);
		$sub_value[2]=substr($value,-12,-8);
		$str1="";
		$str2="";
		$str3="";

		for($i=0; $i <strlen($sub_value[0]) ; $i++)
		{
			$temp=substr($sub_value[0],-$i-1,1);
			if($temp && $temp!="0")
			{
			$str1=$num_to_won[$temp].$down_cate[$i].$str1;
				}
		}

		for($i=0; $i <strlen($sub_value[1]) ; $i++)
		{
			$temp=substr($sub_value[1],-$i-1,1);
			if($temp && $temp!="0")
			{
				$str2=$num_to_won[$temp].$down_cate[$i].$str2;
			}
		}

		if($sub_value[1])
		{
			$str2.="만 ";
		}

		for($i=0; $i <strlen($sub_value[2]) ; $i++)
		{
			$temp=substr($sub_value[2],-$i-1,1);
			if($temp && $temp!="0")
			{
				$str3=$num_to_won[$temp].$down_cate[$i].$str3;
			}
		}

		if($sub_value[2])
		{
			$str3.="억 ";
		}

		if($value == 0){
			return "영";
		}else{
			return $str3.$str2.$str1;
		}
	}
}


// 사업자번호에 - 붙이기
function make_saupja_num($num)
{
	if(strlen(trim($num)) == 10){
		$new_num = substr(trim($num), 0, 3)."-".substr(trim($num), 3, 2)."-".substr(trim($num), 5, 5);
	}else{
		$new_num = trim($num);
	}

	return $new_num;
}

// 생일만들기
function make_birth_date($jumin)
{
	$chknum = substr($juminno, 6, 1);
	$century = ($chknum < 3) ? 1900 : 2000;
	return ($century + substr($jumin, 0, 2)) . "-" . substr($jumin, 2, 2) . "-" . substr($jumin, 4, 2);
}

// 전화번호 각자리멸 만들기
function make_email_part($email, $rt = 0)
{
	$email_arr = explode("@", $email);
	$email1 = $email_arr[0];
	$email2 = $email_arr[1];

	if($rt >= 1 && $re <= 2){
		return ${"email".$rt};
	}else{
		return $email1."@".$email2;
	}
}

// 전화번호 각자리멸 만들기
function make_tel_number($num, $rt = 0)
{
	$num_arr = explode("-", $num);
	$num1 = $num_arr[0];
	$num2 = $num_arr[1];
	$num3 = $num_arr[2];

	if($rt >= 1 && $re <= 3){
		return ${"num".$rt};
	}else{
		return $num1."-".$num2."-".$num3;
	}
}

//전화번호 자르기
function split_tel_number( $Number) 
{
	$sRet = "";
	$Number = Trim( $Number);
	$Number = str_replace( " ", "-", $Number);
	$Number = str_replace( "/", "-", $Number);
	$Number = str_replace( ",", "-", $Number);
	$pos = strpos( $Number, "-");
	if( $pos === false) {  // - 없으면

		if( strlen( $Number) >= 9) {
			$pos = strpos( $Number, "02");
			if( $pos === 0) {
				// 02 경우
				if( strlen( $Number) == 9) {
					$sRet = sprintf( "%s-%s-%s", substr( $Number, 0, 2), substr( $Number, 2, 3), substr( $Number, 5, 4));
				} else {
					$sRet = sprintf( "%s-%s-%s", substr( $Number, 0, 2), substr( $Number, 2, 4), substr( $Number, 6, 100));
				}
			} else {
				// 010, 031 등
				if( strlen( $Number) == 10) {
					$sRet = sprintf( "%s-%s-%s", substr( $Number, 0, 3), substr( $Number, 3, 3), substr( $Number, 6, 4));
				} else {
					$sRet = sprintf( "%s-%s-%s", substr( $Number, 0, 3), substr( $Number, 3, 4), substr( $Number, 7, 100));
				}
			}
		} else {
			$sRet = $Number;
		}
	} else {
		$sRet = $Number;
	}
	return $sRet;
}

function make_option_from_data($data, $value_fld, $name_field, $val = "")
{
	$str = "";
	if(is_array($data) && custom_count($data) > 0){
		for($i=0;$i<custom_count($data);$i++){
			$selected = ($data[$i][$value_fld] == $val) ? 'selected' : '';
			$name_field_arr = explode("|||", $name_field);
			if(custom_count($name_field_arr) > 1){
				$names = $data[$i][$name_field_arr[0]]." (".$data[$i][$name_field_arr[1]].")";
			}else{
				$names = $data[$i][$name_field];
			}
			$str .= '<option value="'.$data[$i][$value_fld].'" '.$selected.'>'.$names.'</option>';
		}
	}
	return $str;
}

function make_radio_from_data($data, $radio_name, $val = "", $value_fld = "val", $name_field = "txt")
{
	$str = "";
	$comma = "";
	if(is_array($data) && custom_count($data) > 0){
		for($i=0;$i<custom_count($data);$i++){
			$checked = ($data[$i][$value_fld] == $val) ? 'checked' : '';
			$str .= $comma.'<input type="radio" name="'.$radio_name.'" value="'.$data[$i][$value_fld].'" '.$checked.'>'.$data[$i][$name_field];
			$comma = "&nbsp;&nbsp;&nbsp;";
		}
	}
	return $str;
}

function get_txt_from_data1($data, $val)
{
	for($i=0;$i<custom_count($data);$i++){
		if($data[$i] == $val){
			return $data[$i];
		}
	}
}

function get_txt_from_data($data, $val, $value_fld = "val", $name_field = "txt")
{
	for($i=0;$i<custom_count($data);$i++){
		if($data[$i][$value_fld] == $val){
			return $data[$i][$name_field];
		}
	}
}

function get_txt_from_data2($data, $val, $comma = ",", $comma2 = ", ", $value_fld = "val", $name_field = "txt")
{
	$str = "";
	if($val){
		$val_arr = explode($comma, $val);
		if(custom_count($val_arr) > 0){
			foreach($val_arr as $k => $v){
				for($i=0;$i<custom_count($data);$i++){
					if($data[$i][$value_fld] == $v){
						$str .= $com . $data[$i][$name_field];
						$com = $comma2;
					}
				}
			}
		}
	}
	return $str;
}

function make_array_to_text($arr, $comma = ", ")
{
	$str = "";
	if(is_array($arr)){
		foreach($arr as $k => $v){
			if(trim($v)){
				$str .= $com.$v;
				$com = $comma;
			}
		}
	}

	return $str;
}

// 이미지 사이즈
function cal_img_size($org_w, $org_h, $w, $h)
{
	$org_ratio = $org_w / $org_h;
	$ratio = $w / $h;
	if($org_ratio > $ratio){
		$r_w = $w;
		$r_h = (int)($w / $org_ratio);
	}else{
		$r_h = $h;
		$r_w = (int)($h * $org_ratio);
	}

	$tmp = array();
	$tmp[w] = $r_w;
	$tmp[h] = $r_h;
	return $tmp;
}

// 이미지 사이즈2
function cal_img_size2($org_w, $org_h, $w, $h)
{
	$org_ratio = $org_w / $org_h;
	$ratio = $w / $h;
	if($org_ratio > $ratio){
		$r_w = $w;
		$r_h = (int)($w / $org_ratio);
	}else{
		$r_h = $h;
		$r_w = (int)($h * $org_ratio);
	}

	if($org_w < $w && $org_h < $h){
		$r_w = $org_w;
		$r_h = $org_h;
	}

	$tmp = array();
	$tmp[w] = $r_w;
	$tmp[h] = $r_h;
	return $tmp;
}

// 파일 확장자 구하기
function get_file_ext($filename)
{
	global $_cfg;
	$arr = explode(".", $filename);
	$ext = $arr[(custom_count($arr) - 1)];
	return $ext;
}

// D-day 구하기
function get_d_day($date)
{
	$dday = Ceil((strtotime($date) - time()) / 86400);
	return $dday;
}

function get_d_day2($date2, $date1)
{
	$dday = Ceil((strtotime($date1) - strtotime($date2)) / 86400) + 1;
	return $dday;
}

// URL에서 GET 값구하기
function get_get_value($url, $arg)
{
	$address = parse_url($url);
	$arr1 = explode("&", $address['query']);
	if(is_array($arr1) && custom_count($arr1) > 0){
		foreach($arr1 as $k => $v){
			$tmp = explode("=", $v);
			if($tmp[0] == $arg){
				return $tmp[1];
			}
		}
	}else{
		return;
	}

	return;
}

// 나이 계산하기
function cal_age($birth, $sl = "s")
{
	if(!$birth) return 0;

	if($sl == "s"){
		$b_arr = explode("-", $birth);
		return date("Y") - $b_arr[0] + 1;
	}else{
		$b_arr = explode("-", $birth);
		$age = (int)date("Y") - (int)$b_arr[0];
		//return (int)date("m");
		if((int)date("m") - (int)$b_arr[1] < 0){
			$age = $age - 1;
		}else if((int)date("m") - (int)$b_arr[1] == 0){
			if((int)date("d") - (int)$b_arr[2] < 0){
				$age = $age - 1;
			}
		}
		return $age;
	}
}

// ** 만들기
function make_blind_str($str, $len, $mode = "name", $blind = "*")
{
	if($mode == "name" && $str != ""){
		$t_len = mb_strlen($str, "UTF-8");
		$b_len = ($t_len - $len > 0) ? $t_len - $len : 0;
		$b_str = "";
		for($i=0;$i<$b_len;$i++) $b_str .= $blind;

		$rslt = mb_substr($str, 0, $len, "UTF-8").$b_str;
	}else if($mode == "phone" && $str != ""){
		$str_arr = explode("-", $str);
		$str = $str_arr[1];
		$t_len = mb_strlen($str, "UTF-8");
		$b_str = "";
		for($i=0;$i<$t_len;$i++) $b_str .= $blind;

		$rslt = $str_arr[0]."-".$b_str."-".$str_arr[2];
	}else if($mode == "email" && $str != ""){
		$str_arr = explode("@", $str);
		$str = $str_arr[0];
		$t_len = mb_strlen($str, "UTF-8");
		$b_len = ($t_len - $len > 0) ? $t_len - $len : 0;
		$b_str = "";
		for($i=0;$i<$b_len;$i++) $b_str .= $blind;

		$rslt = mb_substr($str, 0, $len, "UTF-8").$b_str."@".$str_arr[1];
	}else if($mode == "phoneall" && $str != ""){
		$str_arr = explode("-", $str);
		$rslt = make_blind_str($str_arr[0], 0, 'name', $blind)."-".make_blind_str($str_arr[1], 0, 'name', $blind)."-".make_blind_str($str_arr[2], 0, 'name', $blind);
	}else{
		$rslt = $str;
	}

	return $rslt;
}

// 사용자로 로그인하기 만들기
function open_user_new_window($mb_id, $get_bar = "", $title = "ⓒ")
{
	echo ' <a href="javascript:;" onClick="open_user_new_window(\''.$mb_id.'\', \''.$get_bar.'\');" title="['.$mb_id.'] 사용자로 로그인하기" style="color:red;">'.$title.'</a>';
}

// 시간 만들기
function make_time_date($datetime)
{
	if(date("Y", strtotime($datetime)) != date("Y")){
		return date("y년m월d일", strtotime($datetime));
	}else if(date("Y-m-d", strtotime($datetime)) != date("Y-m-d")){
		return date("m월d일", strtotime($datetime));
	}else if(time() - strtotime($datetime) < 60){
		return (time() - strtotime($datetime))."초전";
	}else if(time() - strtotime($datetime) < 3600 && time() - strtotime($datetime) > 60){
		return floor((time() - strtotime($datetime)) / 60)."분전";
	}else{
		$hour = floor(((time() - strtotime($datetime))/3600))."시간";
		$minute = floor(((time() - strtotime($datetime))%3600) / 60);
		$rslt = ($minute == 0) ? $hour : $hour." ".$minute."분전";
		return $rslt;
	}
}

// 랭킹만들기
function make_ranking_write($table, $field_rank, $order_by, $where = "1")
{
	// 랭킹만들기
	sql_query("SET @cn := 0 ");
	sql_query("UPDATE $table SET $field_rank = @CN:=@CN+1 where $where order by $order_by ");
}

//정렬 타이틀 만들기
function make_order_title_html($title_name, $order_field)
{
	global $order_by;
	global $query_order;

	$asc_desc = "asc";
	if($order_by){
		$order_by_arr = explode(" ", $order_by);
		$asc_desc2 = ($order_by_arr[1] == "asc") ? "desc" : "asc";
		$asc_desc = ($order_by_arr[0] == $order_field) ? $asc_desc2 : $asc_desc;
	}

	return $str = '<a href="'.$_SERVER['PHP_SELF'].'?'.$query_order.'&order_by='.urlencode($order_field." ".$asc_desc).'">'.$title_name.'</a>';

}

//디렉토리 파일목록 가져오기
function make_file_list($folder_name, $arg = array()){
	$rslt = array();

	if(!is_array($arg)){
		$tmps = $arg;
		unset($arg);
		$arg = array();
		if($tmps != "") $arg[] = $tmps;
	}

	if(is_dir($folder_name)) {
		$dir_obj=opendir($folder_name);
		while(($file_str = readdir($dir_obj))!==false){
			
			//echo $file_str;
			if($file_str!="." && $file_str!=".." && !is_dir($folder_name."/".$file_str) && $file_str != "thumb"){

				if(custom_count($arg) == 0 || (custom_count($arg) > 0 && in_array(get_file_ext($file_str), $arg))){
					$temp = array();
					$temp['val'] = $file_str;
					$temp['txt'] = $file_str;
					$rslt[] = $temp;
				}
			}
		}
		closedir($dir_obj);
	}

	return $rslt;
}

//디렉토리 목록 가져오기
function make_dir_list($folder_name){
	$rslt = array();
	if(is_dir($folder_name)) {
		$dir_obj=opendir($folder_name);
		while(($file_str = readdir($dir_obj))!==false){
			
			//echo $file_str;
			if($file_str!="." && $file_str!=".." && is_dir($folder_name."/".$file_str) && $file_str != "thumb"){
				$temp = array();
				$temp['val'] = $file_str;
				$temp['txt'] = $file_str;
				$rslt[] = $temp;
			}
		}
		closedir($dir_obj);
	}

	return $rslt;
}

function copy_dir_all($org_dir, $tgt_dir){
	if(is_dir($org_dir)) {
		$dir_obj=opendir($org_dir);
		while(($file_str = readdir($dir_obj))!==false){
			
			//echo $file_str;
			if($file_str!="." && $file_str!=".."){
				if(is_dir($org_dir."/".$file_str)){
					@mkdir($tgt_dir."/".$file_str, 0777);
					@chmod($tgt_dir."/".$file_str, 0777);
					copy_dir_all($org_dir."/".$file_str, $tgt_dir."/".$file_str);
				}else if(is_file($org_dir."/".$file_str)){
					@copy($org_dir."/".$file_str, $tgt_dir."/".$file_str);
					@chmod($tgt_dir."/".$file_str, 0666);
				}
			}
		}
		closedir($dir_obj);
	}
}

function del_dir_all($dir){
   $files = array_diff(scandir($dir), array('.','..'));
    foreach ($files as $file) {
      if(is_dir("$dir/$file")){
		  del_dir_all("$dir/$file");
	  }else{
		  @unlink("$dir/$file");
	  }
    }
    return @rmdir($dir);
}

function get_dir_total_size($dir = "", $unit = "", $mode = "s")
{
 $mode = (in_array($mode, array("s", "S"))) ? $mode : "s";
 $unit = (in_array(strtolower($unit), array("", "k", "m"))) ? strtolower($unit) : "";
 $web_root = ($dir) ? $_SERVER['DOCUMENT_ROOT']."/".$dir : $_SERVER['DOCUMENT_ROOT'];
 exec("du -".$mode.$unit." ".$web_root, $du);

 if($mode == "s"){
  $rslt = (int)str_replace($web_root, "", $du[0]);
 }else{
  $rslt = array();
  foreach($du as $k => $v){
   $tmp = array();
   $rst = explode($web_root, $v);
   $tmp['size'] = (int)$rst[0];
   $tmp['directory'] = $web_root.$rst[1];
   $rslt[] = $tmp;
  }
 }

 return $rslt;
}

// ----------- 회원관련

//어드민 정보를 얻는다
function get_admin()
{
	global $_cfg;
	
	return sql_fetch(" select * from rb_member where mb_level = '".$_cfg['admin_level']."' order by mb_idx asc ");
}

// 회원 정보를 얻는다.
function get_member($mb_id, $where ='', $fields='*')
{
    global $_cfg;
	

    return sql_fetch(" select $fields from rb_member where mb_id = TRIM('$mb_id') $where ");
}

function get_member2($mb_idx, $where ='', $fields='*')
{
    global $_cfg;
	

    return sql_fetch(" select $fields from rb_member where mb_idx = '$mb_idx' $where ");
}

// 회원 삭제
function delete_member($mb_idx)
{
    global $_cfg;
	

    return sql_query(" update rb_member set mb_status = 0, mb_email = ''  where mb_idx = '$mb_idx'");
}

function delete_member2($mb_id)
{
    global $_cfg;
	

    return sql_query(" update rb_member set mb_status = 0, mb_email = ''  where mb_id = '$mb_id'");
}

//--------- 게시판
// 중복등록 방지 코드 만들기
function make_secure_code()
{
	$secure_code = md5(uniqid(rand(), TRUE));
	$_SESSION['secure_code'] = $secure_code;
	return $secure_code;
}

function check_secure_code($arg = "")
{
	if($_POST['secure_code'] != $_SESSION['secure_code'] || !$_SESSION['secure_code']){
		unset($_SESSION['secure_code']);
		if($arg){
			echo "잘못된 접근입니다.";
			exit;
		}else{
			alert("잘못된 접근입니다.", "/");
			exit;
		}
	}else{
		$_SESSION['secure_code'] = "";
	}
}

/*
어드민 게시판
*/
function get_bd_num_admin($bc_code)
{
	$query1 = "select (max(bd_num) + 1) as mx from rb_board where bc_code = '$bc_code' ";
	//$result = sql_query($query1);
	$row = sql_fetch($query1);
	$_max = $row['mx'];
	return $_max;
}

function push_bd_num_admin($bc_code, $bd_num)
{
	global $branchdb_board;
	$query = "update rb_board set bd_num = (bd_num + 1) where bd_num >= '$bd_num' and bc_code = '$bc_code' ";
	$reslut = sql_query($query);
}

function get_cm_num_admin($bd_idx)
{
	global $branchdb_comment;
	$query1 = "select (max(cm_num) + 1) as mx from rb_comment where bd_idx = '$bd_idx' ";
	//$result = sql_query($query1);
	$row = sql_fetch($query1);
	$_max = $row['mx'];
	return $_max;
}

function push_cm_num_admin($bd_idx, $cm_num)
{
	global $branchdb_comment;
	$query = "update rb_comment set cm_num = (cm_num + 1) where cm_num >= '$cm_num' and bd_idx = '$bd_idx' ";
	$reslut = sql_query($query);
}

//--------------->

function get_bd_num($bc_code)
{
	
	$query1 = "select (max(bd_num) + 1) as mx from rb_board where bc_code = '$bc_code' ";
	//$result = sql_query($query1);
	$row = sql_fetch($query1);
	$_max = $row['mx'];
	return $_max;
}

function push_bd_num($bc_code, $bd_num)
{
	
	$query = "update rb_board set bd_num = (bd_num + 1) where bd_num >= '$bd_num' and bc_code = '$bc_code' ";
	$reslut = sql_query($query);
}

function get_cm_num($bd_idx)
{
	
	$query1 = "select (max(cm_num) + 1) as mx from rb_comment where bd_idx = '$bd_idx' ";
	//$result = sql_query($query1);
	$row = sql_fetch($query1);
	$_max = $row['mx'];
	return $_max;
}

function push_cm_num($bd_idx, $cm_num)
{
	
	$query = "update rb_comment set cm_num = (cm_num + 1) where cm_num >= '$cm_num' and bd_idx = '$bd_idx' ";
	$reslut = sql_query($query);
}

function check_board_admin($bc_code)
{
	global $board_config_data;
	global $member;
	global $is_super;
	$is_board_admin = false;

	for($i=0;$i<custom_count($board_config_data);$i++){
		if($board_config_data[$i]['bc_code'] == $bc_code){
			$board_config = $board_config_data[$i];
		}
	}
	if(!$board_config['bc_code']){
		$is_board_admin = false;
	}

	if($board_config['bc_admin_id']){
		$board_admin_arr = explode(",", $board_config['bc_admin_id']);
		if(in_array($member['mb_id'], $board_admin_arr) || $is_super){
			$is_board_admin = true;
		}
	}

	return $is_board_admin;
}

function delete_board_article($bd_idx)
{
	global $_cfg;

	sql_query("delete from rb_board where bd_idx = '$bd_idx'");

	$f_list = sql_list("select * from rb_board_file where bd_idx = '$bd_idx'");

	for($i=0;$i<custom_count($f_list);$i++){
		@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/".$f_list[$i]['fi_name']);
		@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/thumb_".$f_list[$i]['fi_name']);
	}
	sql_query("delete from  rb_board_file  where bd_idx = '".$bd_idx."'");
	//sql_query("delete from  rb_board_history  where bd_idx = '".$bd_idx."'");

	sql_query("delete from  rb_board_comment  where bd_idx = '".$bd_idx."'");
	sql_query("delete from  rb_board_scrap  where bd_idx = '".$bd_idx."'");

	$p_list = sql_list("select * from rb_board where bd_parent = '".$_GET['bd_idx']."'");
	for($j=0;$j<custom_count($p_list);$j++){
		delete_board_article($p_list[$j]['bd_idx']);
	}
}

function delete_product_article($pd_idx)
{
	global $_cfg;

	sql_query("delete from rb_product where pd_idx = '$pd_idx'");

	$f_list = sql_list("select * from rb_product_file where pd_idx = '$pd_idx'");

	for($i=0;$i<custom_count($f_list);$i++){
		@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/".$f_list[$i]['fi_name']);
		@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/thumb_".$f_list[$i]['fi_name']);
		@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/thumb2_".$f_list[$i]['fi_name']);
	}

	//파일삭제
	$field_arr = array("pd_img");
	$data = sql_fetch("select * from rb_product as b where b.pd_idx = '$pd_idx'");
	foreach($field_arr as $k => $v){
		@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/".$data[$v]);
		@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/thumb_".$data[$v]);
		@unlink($_cfg['web_home'].$_cfg['data_dir']."/files/thumb2_".$data[$v]);
	}
	
	sql_query("delete from  rb_product_file  where pd_idx = '".$pd_idx."'");
	// sql_query("delete from rb_product_cate where pd_idx = '$pd_idx'");
	// sql_query("delete from rb_product_group where pd_idx = '$pd_idx'");
	// sql_query("delete from rb_product_relation where pd_idx = '$pd_idx'");
	//sql_query("delete from  rb_product_history  where pd_idx = '".$pd_idx."'");

	sql_query("delete from  rb_product_comment  where pd_idx = '".$pd_idx."'");
	sql_query("delete from  rb_product_scrap  where pd_idx = '".$pd_idx."'");
}


function is_secret_article($board_config, $bd_idx, $bd_is_secret, $mb_id)
{
	global $is_admin, $member;
	if($board_config['is_secret'] && $bd_is_secret == '1' && ($mb_id == '' || ($mb_id != '' && $mb_id != $member['mb_id'])) && $_SESSION['board_view'] != $bd_idx && !$is_admin){
		return true;
	}else{
		return false;
	}

}
//파일용량 
function file_size($size){

	if($size>0 && $size>=1048576) $result = (int)($size/1024/1024)."MB";
	else if($size>0 && $size<1048576) $result = (int)($size/1024)."KB";
	else if($size>0 && $size<1024) $result = (int)($size)."B";
	else $result = "0KB";
	return $result;
}

//--------- api 관련
// 랜덤스트링 만들기
function make_random_numstring($num)
{
	$arr = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");

	$str = "";
	for($i=0;$i<$num;$i++){
		shuffle($arr);
		$str .= $arr[0];
	}
	return $str;
}

function make_random_string($num)
{
	$arr = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");

	$str = "";
	for($i=0;$i<$num;$i++){
		shuffle($arr);
		$str .= $arr[0];
	}
	return $str;
}

function make_random_string_and_num($num, $option = 1)
{
	$arr = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");

	$str = "";
	for($i=0;$i<$num;$i++){
		shuffle($arr);
		$str .= $arr[0];
	}

	if($option == 1){
		return strtoupper($str);
	}else{
		return $str;
	}
}

// access_token 만들기
function get_access_token($mb_id)
{
	global $_cfg;
	
	$mb = get_member($mb_id);
	$arr = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
	$arr2 = array("1", "2", "3", "4", "5", "6", "7", "8", "9");

	if($mb['mb_idx']){
		$mb_idx = $mb['mb_idx'];
		$len = strlen($mb_idx);

		shuffle($arr2);
		$tok = $arr2[0];

		$tok .= make_zero_first($len, 2);

		
		for($i=0;$i<(11-$len);$i++){
			shuffle($arr);
			$tok .= $arr[0];
		}

		$tok .= ($mb_idx + 0);
		$dtime = time() + 600;

		sql_query("update rb_member set mb_token_expire = '".date("Y-m-d H:i:s", $dtime)."', mb_access_token = '".$tok."' where mb_idx = '$mb_idx'");
	}

	$arr2 = array();
	$arr2['tok'] = (int)$tok;
	$arr2['dtime'] = date("Y-m-d H:i:s", $dtime);

	return $arr2;
}

// access_token 체크
function check_access_token($tok)
{
	global $_cfg;
	

	$len = substr($tok, 1, 2) + 0;

	$mb_idx = substr($tok, (0 - $len)) + 0;
	$mb = sql_fetch("select * from rb_member where mb_idx = '$mb_idx' and mb_certified = 1 and mb_status = 1");

	if($mb['mb_idx']){
		if($tok == $mb['mb_access_token'] && date("Y-m-d H:i:s") < $mb['mb_token_expire']){
			$mb_id = $mb['mb_id'];
			return $mb_id;
		}else{
			return $mb_id;
		}
	}else{
		return $mb_id;
	}
}

// access key expire time 만들기
function make_access_key_expire($mb_id)
{
	global $_cfg;
	

	//$mb_access_key_expire = date("Y-m-d H:i:s", strtotime("+3 hours", time()));
	$mb_access_key_expire = date("Y-m-d H:i:s", (time() + (86400 * 365)));

	sql_query("update rb_member set mb_access_key_expire = '$mb_access_key_expire' where mb_id = '$mb_id'");

	return $mb_access_key_expire;
}

// access key expire time check
function check_access_key_expire($mb_id)
{
	global $_cfg;

	$mb = get_member($mb_id);

	if($mb['mb_access_key_expire'] < date("Y-m-d H:i:s")){
		return true;
	}else{
		return false;
	}
}

function array_sort($array, $on, $order=SORT_ASC)
{
    $new_array = array();
    $sortable_array = array();

    if (custom_count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
            break;
            case SORT_DESC:
                arsort($sortable_array);
            break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}


// 숫자를 패턴(#)을 이용해 변경
function num_text($num="", $pattens="", $string="0", $counts=5)
{
	if($pattens)
	{
		/*---------------------------------------------
		| 패턴에 사용된 # 코드의 갯수를 체크하고 적용
		---------------------------------------------*/
		$shap_count = substr_count($pattens, "#");
		$return_num = str_repeat($string, $shap_count - strlen($num)) . $num;
		/*---------------------------------------------
		| 패턴이 있을 경우에 패턴 출력
		---------------------------------------------*/
		for($i = 0;$i<=strlen($pattens)-1;$i++)
		{
			if($pattens{$i} == "#"){
				$returnnum .= $return_num{$text_count};
				$text_count++;
			}else{
				$returnnum .= $pattens{$i};
			}
		}

	}else{
	  $returnnum = str_repeat($string, $counts - strlen($num)) . $num;
	}

	return $returnnum;
}

// 기본 param 추가하기
function add_basic_param($param)
{
	global $_basic_param;

	$new_param_arr = $_basic_param;

	if($param != ""){
		$param_arr = explode("&", $param);
		if(custom_count($param_arr) > 0){
			foreach($param_arr as $value) {
				$param_arr2 = explode("=", $value);
				$new_param_arr[$param_arr2[0]] = urlencode($param_arr2[1]);
			}
		}
	}

	if(custom_count($new_param_arr) > 0){
		$rslt_arr = array();
		foreach($new_param_arr as $k => $v) {
			$rslt_arr[] = $k."=".$v;
		}
		return implode("&", $rslt_arr);
	}
}


//파일의 이미지여부 확인
function check_is_image($file)
{
	global $_cfg;
	if(file_exists($_cfg['web_home'].$_cfg['data_dir']."/files/".$file)){
		$timg = @getimagesize($_cfg['web_home'].$_cfg['data_dir']."/files/".$file);
		if($timg[2] != 1 && $timg[2] != 2 && $timg[2] != 3){
			return 0;
		}else{
			return 1;
		}
	}else{
		return 0;
	}
}


//유튜브 iframe 자동재생 주소로 바꾸기
function change_youtube_autoplay($str, $stop = 0)
{
	//echo $str;
	$pattern = "/<iframe[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i";
	preg_match_all($pattern, $str, $matches);
	//preg_match($pattern, $str, $matches, PREG_OFFSET_CAPTURE);
	//print_r($matches);
	// <iframe width="560" height="315" src="https://www.youtube.com/embed/JxbcS4-CH70?controls=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>

	if($matches[1][0]){
		$url = $matches[1][0];
		$address = parse_url($url);
		if($address['host'] == "www.youtube.com" && substr($address['path'], 0, 7) == "/embed/"){
			if($stop == 0){
				$new_str = $address['scheme']."://www.youtube.com".$address['host'].$address['path']."?version=2&autohide=1&autoplay=1&loop=1";
				$new_str = "http://www.youtube-loop.com".$address['path']."?version=2&autohide=1&autoplay=1&loop=1&controls=0&showinfo=0&rel=0";
				return str_replace($matches[1][0], $new_str, $str);
			}
		}
	}

	return $str;
}

function munja_send($mtype, $name, $phone, $msg, $callback, $contents, $reserve="", $reserve_time="", $etc1="", $etc2="") {
	global $_cfg;

	$host = "www.mdalin.co.kr"; //홈페이지 주소
	$id = "dqnetworks"; // 아이디 입력
	$pass = "dntjr0330"; // 비밀번호 입력
	$param = "remote_id=".$id;
	$param .= "&remote_pass=".$pass;
	$param .= "&remote_reserve=".$reserve;
	$param .= "&remote_reservetime=".$reserve_time;
	$param .= "&remote_name=".$name;
	$param .= "&remote_phone=".$phone;
	$param .= "&remote_callback=".$callback;
	if ($mtype == "lms" || $mtype == "mms") { $param .= "&remote_subject=".iconv("UTF-8", "EUC-KR", $_cfg['site_name']." 알림");}
	$param .= "&remote_msg=".$msg;
	$param .= "&remote_contents=".$contents;
	$param .= "&remote_etc1=".$etc1;
	$param .= "&remote_etc2=".$etc2;

	if ($mtype == "lms" || $mtype == "mms") {
		$path = "/Remote/RemoteMms.html";
	} else {
		$path = "/Remote/RemoteSms.html";
	}

	$fp = @fsockopen($host,80,$errno,$errstr,30);
	$return = "";

	if (!$fp) {
		return $_err.$errstr.$errno;
		die($_err.$errstr.$errno);
	} else {
		fputs($fp, "POST ".$path." HTTP/1.1\r\n");
		fputs($fp, "Host: ".$host."\r\n");
		fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
		fputs($fp, "Content-length: ".strlen($param)."\r\n");
		fputs($fp, "Connection: close\r\n\r\n");
		fputs($fp, $param."\r\n\r\n");
		while(!feof($fp)) $return .= fgets($fp,4096);
	}
	fclose ($fp);
	//return $phone;

	//return $return;
	$_temp_array = explode("\r\n\r\n", $return);
	$_temp_array2 = explode("\r\n", $_temp_array[1]);

	if (sizeof($_temp_array2) > 1) {
		$return_string = $_temp_array2[1];
	} else {
		$return_string = $_temp_array2[0];
	}
	return $return_string;
}// end fuction

function record_bad_connect()
{
	global $member;
	global $_cfg;
	global $is_from_super;

	if(!$is_from_super){
		$chk = sql_fetch("select * from rb_bad_log where bl_ip = '".$_SERVER['REMOTE_ADDR']."'");
		if($chk['bl_idx']){
			header("Content-type: text/html; charset=UTF-8");
			die ("비정상적인 접속기록으로 접근이 차단되었습니다..");
		}
	}

	$tmp_url = $_cfg["url"];

	$slen = strlen($tmp_url);

	if(substr($_SERVER['HTTP_REFERER'], 0, $slen) != $tmp_url && strpos($_SERVER["PHP_SELF"], "_save.php") !== false){
		$mb_id = $member['mb_id'];
		$param_arr = array();
		$param_arr['url'] = $_SERVER['PHP_SELF'];
		$param_arr['param_get'] = $_GET;
		$param_arr['param_post'] = $_POST;
		sql_query("insert into rb_bad_log set mb_id = '$mb_id' , bl_referer = '".$_SERVER['HTTP_REFERER']."', bl_target = '".$_SERVER['PHP_SELF']."' , bl_param = '".serialize($param_arr)."', bl_ip = '".$_SERVER['REMOTE_ADDR']."', bl_regdate = now()");
		header("Content-type: text/html; charset=UTF-8");
		die ("정상적인 접속이 아닙니다. 비정상적인 접속기록으로 접근이 차단되었습니다..");
		//alert("정상적인 접속이 아닙니다. 비정상적인 접속기록으로 접근이 차단되었습니다.", "/");
	}
}

function get_yoil($date)
{
	$arr = array("일", "월", "화", "수", "목", "금", "토");
	return $arr[date("w", strtotime($date))];
}

function make_end_time($datetime, $time)
{
	return date('Y-m-d H:i:s', strtotime("+ $time hours", strtotime($datetime)));
}

//평점관련
//평점기록
function write_member_point($mb_id, $point, $ph_type, $memo_ko, $memo_en)
{
	$mb = get_member($mb_id);
	if(!$mb['mb_id']){
		return;
	}

	$point = $point + 0;

	sql_query("
		insert into rb_point_history set 
			mb_idx = '".$mb['mb_idx']."',
			mb_id = '".$mb['mb_id']."',
			ph_type = '$ph_type',
			ph_point = '$point',
			ph_memo_ko = '$memo_ko',
			ph_memo_en = '$memo_en',
			ph_regdate = now()
	");

	sql_query("update rb_member set mb_point = mb_point + $point where mb_id = '$mb_id'");
}


//푸쉬서버에 사용자등록
function pushcat_regist_user($member, $sv_code, $mb_os, $mb_regnum)
{
	global $_cfg;

	$mb_id = $member['mb_id'];
	$mb_level = $member['mb_level'];
	$mb_grade = $member['mb_grade'];
	$mb_push = $member['mb_push'];
	$mb_ck = $member['mb_ck'];

	if($_cfg['push_in_server']){
		c_db2();

		$chk1 = sql_fetch("select * from member where tag = '$mb_id' and domain = '$sv_code' and removed = 0");
		$chk2 = sql_fetch("select * from member where os_type = '$mb_os' and token = '$mb_regnum' and removed = 0 and domain = '$sv_code'");
		if($chk1['member_id']){
			if($chk2['member_id'] && $chk2['tag'] != $mb_id){
				sql_query("update member set removed = 1 where member_id = '".$chk2['member_id']."'");
			}
			sql_query("update member set tag2 = '".$member['mb_level']."', tag3 = '".$member['mb_grade']."', recv_on = '".$member['mb_push']."', os_type = '".$mb_os."', token = '".$mb_regnum."', last_time =now() where member_id = '".$chk1['member_id']."'");
			$member_id = $chk1['member_id'];
		}else{
			if($chk2['member_id']){
				sql_query("update member set removed = 1 where member_id = '".$chk2['member_id']."'");
			}
			sql_query("insert into member set tag = '".$member['mb_id']."', tag2 = '".$member['mb_level']."', tag3 = '".$member['mb_grade']."', os_type = '".$mb_os."', recv_on = '".$member['mb_push']."', token = '$mb_regnum', domain = '$sv_code', last_time =now() ");
			$member_id = sql_insert_id();
		}
		c_db1();
		
		$arr = array();
		$arr['result'] = "ok";
		$arr['result_text'] = "";
		$arr['member_id'] = $member_id;
		return $arr;
	}else{

		include_once($_SERVER['DOCUMENT_ROOT']."/lib"."/class.httpEx.php");

		$http = new HttpEx("http://push_admin.lusoft.co.kr/pushcat_regist_user.php");
		$http->setParam("mb_id", $mb_id);
		$http->setParam("mb_level", $mb_level);
		$http->setParam("mb_grade", $mb_grade);
		$http->setParam("mb_push", $mb_push);

		$http->setParam("sv_code", $sv_code);
		$http->setParam("mb_os", $mb_os);
		$http->setParam("mb_regnum", $mb_regnum);
		$http->setParam("dummy", "1");

		if($http->Open("POST")){
			$rslt = $http->SendRequestBody();
			$http->Close();
		}
		// echo $rslt;
		$result = json_decode($rslt, true);

		return $result;
	}
}

//푸쉬서버에서 사용자수 검색
function pushcat_get_registred_cnt($sv_code, $query)
{
	global $_cfg;

	if($_cfg['push_in_server']){
		c_db2();

		$p_cnt = sql_total("select * from member where domain = '$sv_code' and removed = 0 $search_sql");

		c_db1();
		
		$arr = array();
		$arr['result'] = "ok";
		$arr['result_text'] = "";
		$arr['p_cnt'] = $p_cnt;
		return $arr;
	}else{
		include_once($_SERVER['DOCUMENT_ROOT']."/lib"."/class.httpEx.php");

		$http = new HttpEx("http://push_admin.lusoft.co.kr/pushcat_get_registred_cnt.php");
		$http->setParam("sv_code", $sv_code);
		$http->setParam("query", urlencode($query));
		$http->setParam("dummy", "1");

		if($http->Open("POST")){
			$rslt = $http->SendRequestBody();
			$http->Close();
		}
		//echo $rslt;
		$result = json_decode($rslt, true);

		return $result;
	}
}

//푸쉬서버에서 사용자정보 가져오기
function pushcat_get_registred_user($sv_code, $mb_id)
{
	global $_cfg;

	if($_cfg['push_in_server']){

		c_db2();

		$data = sql_fetch("select * from member where domain = '$sv_code' and removed = 0 and tag = '$mb_id'");

		c_db1();

		$arr = array();
		$arr['result'] = "ok";
		$arr['result_text'] = "";
		$arr['user_data'] = $data;
		return $arr;
	}else{

		include_once($_SERVER['DOCUMENT_ROOT']."/lib"."/class.httpEx.php");

		$http = new HttpEx("http://push_admin.lusoft.co.kr/pushcat_get_registred_user.php");
		$http->setParam("sv_code", $sv_code);
		$http->setParam("mb_id", $mb_id);
		$http->setParam("dummy", "1");

		if($http->Open("POST")){
			$rslt = $http->SendRequestBody();
			$http->Close();
		}
		//echo $rslt;
		$result = json_decode($rslt, true);

		return $result;
	}
}

function pushcat_sendpush($target_type, $tag, $tag2, $tag3, $content, $topic='', $domain = '')
{
	global $_cfg;
	global $sv_code;
	global $member;

	if($tag == $member['mb_id']){
		return;
	}

	if($domain == ""){
		$domain = $sv_code;
	}

	include_once($_SERVER['DOCUMENT_ROOT']."/lib"."/class.httpEx.php");

	//$target_type = ($tag != "") ? 0 : $target_type;


	$http = new HttpEx("http://pushcat.lusoft.co.kr:4002/SendPush");
	$http->setParam("target_type", $target_type);
	$http->setParam("tag", $tag);
	$http->setParam("tag2", $tag2);
	$http->setParam("tag3", $tag3);
	$http->setParam("msg", $content['msg']);
	$content['msg'] = urlencode($content['msg']);
	$http->setParam("content", json_encode($content));
	$http->setParam("domain", $domain);
	$http->setParam("topic", $topic);
	$http->setParam("dummy", "1");

	if($http->Open("POST")){
		$rslt = $http->SendRequestBody();
		$http->Close();
	}
	$result = json_decode($rslt, true);

	return $result['mResultCode'];
}

/*
$push_contents = array();
$push_contents['msg'] = $msg_gu;
$push_contents['mode'] = "goto";
$push_contents['url'] = "/member/my_job_view.php?jr_idx=".$jr_idx;

pushcat_sendpush(0, $push_mb_id, '', '', $push_contents);
*/

function pushcat_reserve_push($target_type, $tag, $tag2, $tag3, $content, $topic='', $booking_time = "", $domain = '')
{
	global $_cfg;
	global $sv_code;
	global $member;

	if($domain == ""){
		$domain = $sv_code;
	}

	$booking_time = ($booking_time == "") ? date("Y-m-d H:i:s", strtotime("+1 hours", time())) : date("Y-m-d H:i:s", strtotime($booking_time));


	//$target_type = ($tag != "") ? 0 : $target_type;

	$msg = $content['msg'];
	$content['msg'] = urlencode($content['msg']);
	$content2 = json_encode($content);

	if($_cfg['push_in_server']){
		c_db2();

		$sql = "insert into booking set 
					target_type = '$target_type',
					msg = '$msg',
					content = '$content2',
					tag = '$tag',
					tag2 = '$tag2',
					tag3 = '$tag3',
					create_time = now(),
					booking_time = '$booking_time',
					topic = '$topic',
					domain = '$domain'
		";
		sql_query($sql);
		$booking_id = sql_insert_id();

		c_db1();

		$arr = array();
		$arr['result'] = "ok";
		$arr['result_text'] = "";
		$arr['booking_id'] = $booking_id;

		return $arr;
	}else{


		include_once($_SERVER['DOCUMENT_ROOT']."/lib"."/class.httpEx.php");

		//$target_type = ($tag != "") ? 0 : $target_type;


		$http = new HttpEx("http://push_admin.lusoft.co.kr/pushcat_reserve_push.php");
		$http->setParam("target_type", $target_type);
		$http->setParam("tag", $tag);
		$http->setParam("tag2", $tag2);
		$http->setParam("tag3", $tag3);
		$http->setParam("msg", $content['msg']);
		$http->setParam("content2", $content2);
		$http->setParam("domain", $domain);
		$http->setParam("topic", $topic);
		$http->setParam("booking_time", $booking_time);
		$http->setParam("dummy", "1");

		if($http->Open("POST")){
			$rslt = $http->SendRequestBody();
			$http->Close();
		}
		$result = json_decode($rslt, true);
		return $result;
	}
}

function pushcat_reserve_push_delete($booking_id, $domain = '')
{
	global $_cfg;
	global $sv_code;
	global $member;

	if(!$booking_id){
		return;
	}

	if($domain == ""){
		$domain = $sv_code;
	}

	if($_cfg['push_in_server']){
		c_db2();

		sql_query("delete from booking where booking_id = '$booking_id' and domain = '$domain'");
		c_db1();

		$arr = array();
		$arr['result'] = "ok";
		$arr['result_text'] = "";
		return $arr;

	}else{
		include_once($_SERVER['DOCUMENT_ROOT']."/lib"."/class.httpEx.php");

		//$target_type = ($tag != "") ? 0 : $target_type;


		$http = new HttpEx("http://push_admin.lusoft.co.kr/pushcat_reserve_push_delete.php");
		$http->setParam("domain", $domain);
		$http->setParam("booking_id", $booking_id);
		$http->setParam("dummy", "1");

		if($http->Open("POST")){
			$rslt = $http->SendRequestBody();
			$http->Close();
		}
		$result = json_decode($rslt, true);
		return $result;
	}
}


//소수점이하가 0일때 소수점이하를 없애는 함수
function number_format2($num, $arg = 0)
{
	$f_num = floor($num);
	if($num == $f_num){
		return number_format($num, 0);
	}else{
		return number_format($num, $arg);
	}
}

function only_app()
{
	global $user_agent;
	if($user_agent != "app"){
		alert("잘못된 접근입니다.", "/index.php");
	}
}

function only_web()
{
	global $user_agent;
	if($user_agent != "web"){
		alert("잘못된 접근입니다.", "/index.php");
	}
}

function make_product_option_value($pd_option)
{
	$option = array();
	if($pd_option != ""){
		$option_arr = explode("\n", $pd_option);
		$option = array();
		foreach($option_arr as $k => $v){
			$o_arr = explode(":", trim($v));
			$o_name = trim($o_arr[0]);
			$o_price = (int)trim($o_arr[1]);
			if($o_name != ""){
				$temp = array();
				$temp['o_name'] = $o_name;
				$temp['o_price'] = $o_price;
				$option[] = $temp;
			}
		}
	}
	return $option;
}

function cr_status_process()
{
	global $is_from_supersuper;
	$show_error_cnt = range(1, 1000);
	shuffle($show_error_cnt);

	/*
	if(!$is_from_supersuper && $show_error_cnt[0] < 800){
		header("Content-type: text/html; charset=UTF-8");
		$err1_arr = array("mb_level", "mb_id", "mb_grade");
		$err2_arr = array("/docs/_inc/_common.php", "/docs/lib/lib.common.php", "/docs/_inc/_config.php");
		$err3_arr = range(1, 698);

		shuffle($err1_arr);
		shuffle($err2_arr);
		shuffle($err3_arr);
		if($_SERVER['SCRIPT_NAME'] != "/index.php"){
			goto_url("/index.php");
		}else{
			die ("Warning: Use of undefined constant ".$err1_arr[0]." - assumed '".$err1_arr[0]."' (this will throw an Error in a future version of PHP) in /DATA/openmarket.lusolution.co.kr".$err2_arr[0]." on line ".$err3_arr[0]."");
		}
	}
	*/

	// 4
	sql_query("update rb_coupon_record set cr_status = 4 where cr_status in (1, 3) and cr_e_date < '".date('Y-m-d')."' and cr_e_date != '0000-00-00 00:00:00'");
	// 3
	sql_query("update rb_coupon_record set cr_status = 3 where cr_status in (1, 4) and cr_s_date > '".date('Y-m-d')."' and cr_e_date != '0000-00-00 00:00:00'");
	// 1
	sql_query("update rb_coupon_record set cr_status = 1 where cr_status in (3, 4) and cr_e_date >= '".date('Y-m-d')."' and cr_s_date <= '".date('Y-m-d')."' and cr_e_date != '0000-00-00 00:00:00'");
}

function check_can_use_coupon($product, $coupon, $ct_cnt, $ct_option_price)
{
	global $member, $_cfg;
	$halin_rate = ($product['pd_period'] == 1) ? get_txt_from_data($_cfg['member']['mb_grade'], $member['mb_grade'], 'val', 'halin') : get_txt_from_data($_cfg['member']['mb_grade'], $member['mb_grade'], 'val', 'halin2');

	if($coupon['cp_product_type'] == '2'){
		$cp_product_pd_idx_arr = explode(",", $coupon['cp_product']);
		if(!in_array($product['pd_idx'], $cp_product_pd_idx_arr)){
			return false;
		}
	}

	if($coupon['cp_cate_type'] == '2'){

		$cp_cate_idx_arr = explode(",", $coupon['cp_cate']);
		$is_in = 0;

		foreach($cp_cate_idx_arr as $k => $v){
			$cate_datas_arr = explode(":", $v);
			$tmp_in = 0;

			$c1_idx = $cate_datas_arr[0];
			if($c1_idx == $product['c1_idx']){
				$tmp_in = 1;

				$c2_idx = $cate_datas_arr[1];
				if($c2_idx && $c2_idx == $product['c2_idx']){
					$tmp_in = 1;
					$c3_idx = $cate_datas_arr[2];
					if($c3_idx && $c3_idx == $product['c3_idx']){
						$tmp_in = 1;
					}
				}
			}

			if($tmp_in == 1){
				$is_in = 1;
			}
		}

		if($is_in == 0){
			return false;
		}

	}

	//금액체크
	if($coupon['cp_min_pay_amount'] > (($product['pd_price'] + $ct_option_price) * (1 - $halin_rate) * $ct_cnt)){
		return false;
	}

	return true;
}

//후기숫자와 평점 기록
function set_product_review_result($pd_idx)
{
	$pd_review_cnt = sql_total("select * from rb_product_review where pd_idx = '".$pd_idx."' ");
	$pd_review_avg_d = sql_total("select avg(pr_point) as av from rb_product_review where pd_idx = '".$pd_idx."' ");

	sql_query("update rb_product set pd_review_cnt = '$pd_review_cnt' , pd_review_avg = '".$pd_review_avg_d['av']."' where pd_idx = '".$pd_idx."'");
}

//배송비표시
function display_delivery_amount_list($product)
{
	//p_arr($product);
	if($product['pd_delivery_type2'] == 1){
		$shop = sql_fetch("select * from rb_shop where shop_id = '".$product['shop_id']."'");
		if($shop['sh_delivery_type2'] == 2){
			return '<span class="free">무료배송</span>';
		}else if($shop['sh_delivery_type2'] == 3){
			return '<span class="charged">'.number_format($product['sh_delivery_amount']).'</span>';
		}else if($shop['sh_delivery_type2'] == 4){
			if($shop['sh_delivery_free_amount'] < $product['pd_price'] ){
				return '<span class="free">무료배송</span>';
			}else{
				return '<span class="charged">'.number_format($product['sh_delivery_amount']).'</span>';
			}
		}

	}else if($product['pd_delivery_type2'] == 2){
		return '<span class="free">무료배송</span>';
	}else if($product['pd_delivery_type2'] == 3){
		return '<span class="charged">'.number_format($product['pd_delivery_amount']).'</span>';
	}else if($product['pd_delivery_type2'] == 4){
		if($product['pd_delivery_free_amount'] < $product['pd_price'] ){
			return '<span class="free">무료배송</span>';
		}else{
			return '<span class="charged">'.number_format($product['pd_delivery_amount']).'</span>';
		}
	}
}


function make_search_query($field_arr, $s_arr)
{
	$str_arr = array();
	foreach($field_arr as $row1){
		foreach($s_arr as $row2){
			$str_arr[] = " $row1 like '%$row2%' ";
		}
	}
	if(custom_count($str_arr) > 0){
		return implode(" or " , $str_arr);
	}else{
		return 1;
	}
}

function getAvailableCoupon()
{
	global $_cfg, $member, $is_member;
	if(!$is_member){
		return 0;
	}
	$coupon_cnt = sql_total("select * from rb_coupon_record as cr left join rb_coupon as c on cr.cp_idx = c.cp_idx where cr.mb_id = '$member[mb_id]' and c.cp_use = '1' and cr.cr_status = '1'");
	return $coupon_cnt;
}

function getMyOrder()
{
	global $_cfg, $member, $is_member;
	if(!$is_member){
		return 0;
	}
	$order_cnt = sql_total("select * from rb_order_cart as c left join rb_order as o on c.od_idx = o.od_idx where o.mb_id = '$member[mb_id]'  and o.od_status > 0");
	return $order_cnt;
}

function event_status_process()
{
	sql_query("update rb_event set wi_status1 = 1 where wi_sdate > '".date('Y-m-d')."'");
	sql_query("update rb_event set wi_status1 = 2 where wi_edate >= '".date('Y-m-d')."' and wi_sdate <= '".date('Y-m-d')."'");
	sql_query("update rb_event set wi_status1 = 3 where wi_edate < '".date('Y-m-d')."'");
}


//make_random_string
// $arr = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
function make_otp_secret_string($mb_idx, $num)
{
	$arr2 = array("2", "3", "4", "5", "6", "7", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
	$arr = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");

	$arr3 = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");

	//회원번호 7자리로 만들기
	$idx_len = strlen($mb_idx);
	$idx_str = sprintf('%07d', $mb_idx);
	$idx_str_len = strlen($idx_str) - $idx_len;

	$str1 = "";
	for($i=0;$i<$idx_str_len;$i++){
		shuffle($arr);
		$str1 .= strtoupper($arr[0]);
	}
	for($i=0;$i<$idx_len;$i++){
		$str1 .= strtoupper($arr3[substr($mb_idx, $i, 1)]);
	}

	//회차 7자리로 만들기
	$num_len = strlen($num);
	$num_str = sprintf('%07d', $num);
	$num_str_len = strlen($num_str) - $num_len;

	$str2 = "";
	for($i=0;$i<$num_str_len;$i++){
		shuffle($arr);
		$str2 .= strtoupper($arr[0]);
	}
	for($i=0;$i<$num_len;$i++){
		$str2 .= strtoupper($arr3[substr($num, $i, 1)]);
	}

	return $str1.strtoupper($arr2[0]).strtoupper($arr2[1]).$str2;
}

//다음 결제일 표시
function disp_next_delivery($ct_period, $date, $mode)
{
	global $_cfg;
	$new_date = strtotime(get_txt_from_data($_cfg['order']['od_period'], $ct_period, 'val', 'val2'), strtotime($date));
	if($mode == 1){
		//return date('Y. m. d('.get_yoil(date('Y-m-d', $new_date)).')', $new_date);
		return date('Y. m. d(수)', $new_date);
	}
}

function split_cr_code($cr_code)
{
	return substr($cr_code, 0, 4).'-'.substr($cr_code, 4, 4).'-'.substr($cr_code, 8, 4).'-'.substr($cr_code, 12, 4);
}

function make_cr_code($cc_idx, $num)
{
	$arr2 = array("2", "3", "4", "5", "6", "7", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
	$arr = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
	$arr3 = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");

	$idx_len = strlen($cc_idx);
	$idx_str = sprintf('%07d', $cc_idx);
	$idx_str_len = strlen($idx_str) - $idx_len;

	$str1 = strtoupper($arr3[$idx_len]);
	for($i=0;$i<$idx_str_len;$i++){
		shuffle($arr);
		$str1 .= strtoupper($arr[0]);
	}
	for($i=0;$i<$idx_len;$i++){
		$str1 .= strtoupper($arr3[substr($cc_idx, $i, 1)]);
	}

	//echo $str1;

	$idx_len = strlen($num);
	$idx_str = sprintf('%07d', $num);
	$idx_str_len = strlen($idx_str) - $idx_len;

	$str2 = strtoupper($arr3[$idx_len]);
	for($i=0;$i<$idx_str_len;$i++){
		shuffle($arr);
		$str2 .= strtoupper($arr[0]);
	}
	for($i=0;$i<$idx_len;$i++){
		$str2 .= strtoupper($arr3[substr($num, $i, 1)]);
	}

	//echo $str2;

	return $cr_code = $str1.$str2;
	//echo split_cr_code($cr_code);
}

//정기주문 정보 정리(상품 포함여부, 가격정리)
function fix_order_info_for_period($od_idx)
{
	$order = sql_fetch("select od_idx, mb_id from rb_order where od_idx = '$od_idx' and od_status = '9'");
	if(!$order['od_idx']){
		return;
	}

	$mb_id = $order['mb_id'];

	$mb = get_member($mb_id);

	$od_title = "";

	$cart_list = sql_list("select * from rb_order_cart where od_idx = '$od_idx'");
	//포함된 상품 개수
	$cart_cnt = custom_count($cart_list);
	$cart_c_cnt = $cart_cnt;

	//상품확인 및 가격
	$total_od_amount = 0;	// 할인전 금액
	$total_amount_halin = 0;	// 할인액

	$total_amount_all = 0;	// 쿠폰할인전
	$total_amount_coupon_halin = 0;	// 쿠폰할인액	

	$total_amount = 0;	// 쿠폰할인후

	$total_delivery_amount = 0;	// 배송비

	$od_point = 0;	// 포인트결제 (무조건 0)
	$od_point_l = 0;	// 엘포인트사용액 (무조건 0)

	$total_pay_amount = 0;	// 최종결제금액

	$od_get_point = 0;	// 포인트지급액
	$od_get_point_l = 0;	// 엘포인트사용액지급액 (무조건 0)

	
	//묶음배송인것들의 배송비
	$group_delivery_amount = array();

	//배송비 때문에 나중에 업데이트해야할 목록
	$cart_for_deli = array();
	
	for($i=0;$i<$cart_cnt;$i++){
		//업데이트해야할 항목
		$update_cart_field_arr = array();

		$cart = $cart_list[$i];
		$ct_idx = $cart['ct_idx'];
		$pd_idx = $cart['pd_idx'];
		$pe_idx = $cart['pe_idx'];

		//상품확인 (판매중지여부, 상품존재여부, pet 존재여부, 옵션 존재)
		$product = sql_fetch("select * from rb_product where pd_idx = '$pd_idx' ");
		$pet = sql_fetch("select * from rb_pet where pe_idx = '$pe_idx' and mb_id = '".$mb['mb_id']."'");

		if($od_title == ""){
			$od_title = $product['pd_name'];
		}

		$option1 = make_product_option_value($product['pd_option1']);
		$option2 = make_product_option_value($product['pd_option2']);

		$ct_option1_tmp = ($cart['ct_option1'] != '-' ) ? $cart['ct_option1'] : "";
		//****
		$ct_option1 = $cart['ct_option1'];
		$update_cart_field_arr[] = array("ct_option1", $ct_option1);


		$ct_option2_tmp = ($cart['ct_option2'] != '-' ) ? $cart['ct_option2'] : "";
		//****
		$ct_option2 = $cart['ct_option2'];
		$update_cart_field_arr[] = array("ct_option2", $ct_option2);

		//상품갯수
		//****
		$ct_cnt = $cart['ct_cnt'];
		$update_cart_field_arr[] = array("ct_cnt", $ct_cnt);

		//옵션체크
		$correct_option = 1;

		if(custom_count($option1) > 0 && custom_count($option2) > 0){
			if($ct_option1_tmp == "" || $ct_option2_tmp == ""){
				$correct_option = 0;
			}
		}else if(custom_count($option1) > 0 && custom_count($option2) == 0){
			if($ct_option1_tmp == "" || $ct_option2_tmp != ""){
				$correct_option = 0;
			}			
		}else if(custom_count($option1) == 0 && custom_count($option2) > 0){
			if($ct_option1_tmp != "" || $ct_option2_tmp == ""){
				$correct_option = 0;
			}
		}else{
			if($ct_option1_tmp != "" || $ct_option2_tmp != ""){
				$correct_option = 0;
			}
		}

		$is_test = false;
		if($is_test){
			//echo "select * from rb_pet where pe_idx = '$pe_idx' and mb_id = '".$mb['mb_id']."'";
			//p_arr($pet);
		}


		if(!$product['pd_idx']){
			//없는 상품이면
			if($is_test){
				sql_query("update rb_order_cart set ct_debug = '없는 상품이면' where ct_idx = '$ct_idx'");
			}else{
			sql_query("delete from rb_order_cart where ct_idx = '$ct_idx'");
			$cart_c_cnt--;
			}
		}else if($product['pd_use'] != 1){
			//판매 안하는 상품이면
			if($is_test){
				sql_query("update rb_order_cart set ct_debug = '판매 안하는 상품이면' where ct_idx = '$ct_idx'");
			}else{
			sql_query("delete from rb_order_cart where ct_idx = '$ct_idx'");
			$cart_c_cnt--;
			}
		}else if(!$pet['pe_idx']){
			//반려동물이 없으면
			if($is_test){
				sql_query("update rb_order_cart set ct_debug = '반려동물이 없으면' where ct_idx = '$ct_idx'");
				
			}else{
			sql_query("delete from rb_order_cart where ct_idx = '$ct_idx'");
			$cart_c_cnt--;
			}
		}else if($correct_option == 0){
			//옵션이 정확하지 않으면
			if($is_test){
				sql_query("update rb_order_cart set ct_debug = '옵션이 정확하지 않으면' where ct_idx = '$ct_idx'");
			}else{
			sql_query("delete from rb_order_cart where ct_idx = '$ct_idx'");
			$cart_c_cnt--;
			}
		}else if($ct_cnt <= 0){
			//주문수량이 0이면
			if($is_test){
				sql_query("update rb_order_cart set ct_debug = '주문수량이 0이면' where ct_idx = '$ct_idx'");
			}else{
			sql_query("delete from rb_order_cart where ct_idx = '$ct_idx'");
			$cart_c_cnt--;
			}
		}else{
			//옵션가격
			//****
			if(custom_count($option1) > 0 && custom_count($option2) > 0){
				$ct_option1_price = get_txt_from_data($option1, $ct_option1_tmp, 'o_name', 'o_price');
				$ct_option2_price = get_txt_from_data($option2, $ct_option2_tmp, 'o_name', 'o_price');
			}else if(custom_count($option1) > 0 && custom_count($option2) == 0){
				$ct_option1_price = get_txt_from_data($option1, $ct_option1_tmp, 'o_name', 'o_price');
				$ct_option2_price = 0;
			}else if(custom_count($option1) == 0 && custom_count($option2) > 0){
				$ct_option1_price = 0;
				$ct_option2_price = get_txt_from_data($option2, $ct_option1_tmp, 'o_name', 'o_price');
			}else{
				$ct_option1_price = 0;
				$ct_option2_price = 0;
			}

			$update_cart_field_arr[] = array("ct_option1_price", $ct_option1_price);
			$update_cart_field_arr[] = array("ct_option2_price", $ct_option2_price);

			//옵션변동가
			//****
			$ct_option_price = $ct_option1_price + $ct_option2_price;
			$update_cart_field_arr[] = array("ct_option_price", $ct_option_price);

			//할인전 단가
			//****
			$ct_price = $product['pd_price'] + $ct_option_price;
			$update_cart_field_arr[] = array("ct_price", $ct_price);

			//할인전 단가 * 갯수
			$price_before = $ct_price * $ct_cnt;
			$total_od_amount += $price_before;

			//할인후 단가
			//****
			$ct_price2 = $product['pd_price_period'.$mb['mb_grade']] + $ct_option_price;
			$update_cart_field_arr[] = array("ct_price2", $ct_price2);

			//(할인 후 단가) * 갯수
			//****
			$ct_basic_price = $ct_price2 * $ct_cnt;
			$update_cart_field_arr[] = array("ct_basic_price", $ct_basic_price);
			$total_amount_all += $ct_basic_price;

			$total_amount_halin += ($price_before - $ct_basic_price);

			//****
			$cr_idx = 0;
			$update_cart_field_arr[] = array("cr_idx", $cr_idx);

			$cp_title = "";
			$update_cart_field_arr[] = array("cp_title", $cp_title);

			$ct_coupon_price = 0;
			$update_cart_field_arr[] = array("ct_coupon_price", $ct_coupon_price);
			$total_amount_coupon_halin += $ct_coupon_price;

			//쿠폰 할인 후 가격
			$coupon_price = $price_before - $ct_coupon_price;
			$total_amount += $coupon_price;


			//배송비
			$ct_deli_amount = 0;
			if($product['pd_delivery_type2'] == 1){
				$product['pd_delivery_type2'] = $_cfg['config']['cf_delivery_type2'];
				$product['pd_delivery_amount'] = $_cfg['config']['cf_delivery_amount'];
				$product['pd_delivery_free_amount'] = $_cfg['config']['cf_delivery_free_amount'];
			}

			switch($product['pd_delivery_type2']){
				case "3" :
					if($product['pd_delivery_type'] == '1'){
						$ct_deli_amount = $product['pd_delivery_amount'];
					}else{
						$ct_deli_amount = $product['pd_delivery_amount'] * ceil($ct_cnt / $product['pd_delivery_type_cnt']);
					}
				break;
				case "4" :
					if($coupon_price >= $product['pd_delivery_free_amount']){
						$ct_deli_amount = 0;
					}else if($product['pd_delivery_type'] == '1'){
						$ct_deli_amount = $product['pd_delivery_amount'];
					}else{
						$ct_deli_amount = $product['pd_delivery_amount'] * ceil($ct_cnt / $product['pd_delivery_type_cnt']);
					}
				break;
				case "4" :
					$ct_deli_amount = 0;
				break;
			}

			$update_cart_field_arr[] = array("ct_deli_amount", $ct_deli_amount);

			//묶음배송 가능한건 나중에 최소 배송비를 위해 배열에 넣어 놓기
			if($product['pd_delivery_type'] == '1'){
				$group_delivery_amount[] = $ct_deli_amount;
				$cart_for_deli[] = array(
										"ct_idx" => $ct_idx,
										"coupon_price" => $coupon_price
									);
			}else{
				$total_delivery_amount += $ct_deli_amount;
			}

			//총금액
			$ct_amount = $coupon_price + $ct_deli_amount;
			$update_cart_field_arr[] = array("ct_amount", $ct_amount);
			
			//업데이트
			$sql = "update rb_order_cart set ";
			$sql_update_arr = array();
			//p_arr($update_cart_field_arr);
			foreach($update_cart_field_arr as $row_temp){
				$_field = $row_temp[0];
				$_value = $row_temp[1];
				$sql_update_arr[] = " ".$_field." = '".$_value."' ";
			}

			if($is_test){
				p_arr($sql_update_arr);
			}

			if(custom_count($sql_update_arr) > 0){
				$sql .= implode(",", $sql_update_arr);
			}
			$sql .= " where ct_idx = '$ct_idx' ";
			if($is_test){
				echo "<br><br><br><br><br>";
				echo $sql;
			}else{
				sql_query($sql);
			}

		}
	}

	if(custom_count($cart_for_deli) > 0){
		$min_deli = min($group_delivery_amount);
		foreach($cart_for_deli as $tmp_row){
			$ct_idx = $tmp_row['ct_idx'];
			$coupon_price = $tmp_row['coupon_price'];
			$ct_deli_amount = $min_deli;
			$min_deli = 0;

			$ct_amount = $coupon_price + $ct_deli_amount;

			$total_delivery_amount += $ct_deli_amount;
			sql_query("update rb_order_cart set ct_deli_amount = '$ct_deli_amount', ct_amount = '$ct_idx' where ct_idx = '$ct_idx'");
		}
	}

	if($cart_c_cnt > 1){
		$od_title .= "외 ".number_format($cart_c_cnt - 1)."건";
	}

	//상품이 없는거면 주문을 삭제
	if($cart_c_cnt <= 0){
		if(!$is_test) sql_query("delete from rb_order where od_idx = '$od_idx'");
	}else{

		$total_od_amount = 0;	// 할인전 금액
		$total_amount_halin = 0;	// 할인액

		$total_amount_all = 0;	// 쿠폰할인전
		$total_amount_coupon_halin = 0;	// 쿠폰할인액	

		$total_amount = 0;	// 쿠폰할인후

		$total_delivery_amount = 0;	// 배송비

		$od_point = 0;	// 포인트결제 (무조건 0)
		$od_point_l = 0;	// 엘포인트사용액 (무조건 0)

		// 최종결제금액
		$total_pay_amount = $total_amount + $total_delivery_amount - $od_point - $od_point_l;

		$od_get_point = round(($total_amount - $od_point - $od_point_l) * get_txt_from_data($_cfg['member']['mb_grade'], $mb['mb_grade'], 'val', 'point'));	// 포인트지급액
		$od_get_point_l = 0;	// 엘포인트사용액지급액 (무조건 0)


		//주문서 업데이트
		$sql = "
		update rb_order set 
			od_title = '$od_title',
			total_od_amount = '$total_od_amount',
			total_amount_halin = '$total_amount_halin',

			total_amount_all = '$total_amount_all',
			total_amount_coupon_halin = '$total_amount_coupon_halin',

			total_amount = '$total_amount',

			total_delivery_amount = '$total_delivery_amount',

			od_point = '$od_point',
			od_point_l = '$od_point_l',

			total_pay_amount = '$total_pay_amount',

			od_get_point = '$od_get_point',
			od_get_point_l = '$od_get_point_l'

			where od_idx = '$od_idx'
		";
		sql_query($sql);
	}
}


function lbxc_coin_api() {
	$_param = array(
		'symbol' => 'LBXC-USDT'
	);
	$_url = "https://global-openapi.bithumb.pro/openapi/v1/spot/ticker?".http_build_query($_param);

	$curlObj = curl_init();
	curl_setopt($curlObj, CURLOPT_URL, $_url);
	curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($curlObj, CURLOPT_HEADER, 0);
	$response = curl_exec($curlObj);
	$_json = json_decode($response,true);
	curl_close($curlObj);


	$one_lbxc = $_json['data'][0]['c'];
	$one_dollar = 1 / $one_lbxc;

	$arr = array();
	$arr['one_lbxc'] = $one_lbxc;
	$arr['one_dollar'] = $one_dollar;

	return $arr;
}

function coin_log($mode, $request=array(), $cul=array(), $hash) {
	global $_cfg, $member;

	$sql_log = "insert into _coin_log set 
							mb_idx = '".$member['mb_idx']."',
							col_position = '".$mode."',
							col_request = '".serialize($request)."',
							col_curl = '".serialize($cul)."', 
							col_hash = '".addslashes($hash)."',
							col_regdate = now()
						";
	sql_query($sql_log);
}
?>