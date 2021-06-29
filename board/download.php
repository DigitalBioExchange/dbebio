<?php
include "../inc/_common.php";
include "../_inc/_board_config.php";

$data = sql_fetch("select * from rb_board as b where b.bc_code = '$bc_code' and b.bd_idx = '$bd_idx' ");

if(!$data[bd_idx]){
	header("Content-type: text/html; charset=UTF-8");
	alert("없는 게시글입니다.");
}

if(is_secret_article($board_config, $data[bd_idx], $data[bd_is_secret], $data[mb_id])){
	if($data[bd_idx] != $_SESSION[board_view] && !$is_admin){
		header("Content-type: text/html; charset=UTF-8");
		alert("비밀글입니다..");
	}
}

$data2 = sql_fetch("select * from rb_board_file where bd_idx = '$bd_idx' and fi_idx = '$fi_idx'");
if(!$data2[fi_idx]){
	header("Content-type: text/html; charset=UTF-8");
	alert("없는 파일입니다.");
}

$src = $data2['fi_name'];
$original = urlencode($data2['fi_name_org']);

if (!$original){
	header("Content-type: text/html; charset=UTF-8");
	alert("없는 파일입니다.");
}

$filepath = $tgt = $_cfg['web_home'].$_cfg['data_dir']."/files/".$src;
$filepath = $filepath;
if (!is_file($filepath) || !file_exists($filepath)){
	header("Content-type: text/html; charset=UTF-8");
	alert("없는 파일입니다.");
}


if(preg_match("/msie/i", $_SERVER[HTTP_USER_AGENT]) && preg_match("/5\.5/", $_SERVER[HTTP_USER_AGENT])) {
    header("content-type: doesn/matter");
    header("content-length: ".filesize("$filepath"));
    header("content-disposition: attachment; filename=\"$original\"");
    header("content-transfer-encoding: binary");
} else {
    header("content-type: file/unknown");
    header("content-length: ".filesize("$filepath"));
    header("content-disposition: attachment; filename=\"$original\"");
    header("content-description: php generated data");
}
header("pragma: no-cache");
header("expires: 0");
flush();

$fp = fopen("$filepath", "rb");

// 4.00 대체
// 서버부하를 줄이려면 print 나 echo 또는 while 문을 이용한 방법보다는 이방법이...
//if (!fpassthru($fp)) {
//    fclose($fp);
//}

$download_rate = 10;

while(!feof($fp)) {
    //echo fread($fp, 100*1024);
    /*
    echo fread($fp, 100*1024);
    flush();
    */

    print fread($fp, round($download_rate * 1024));
    flush();
    usleep(1000);
}
fclose ($fp);
flush();
?>