<?php
$no_header = 1;
include "../inc/_common.php";

if($idx && $db_table && $db_idx && $f_file && $f_dir){

	$data = sql_fetch("select * from $db_table where $db_idx = '$idx'");

	$src = $data[$f_file];
	$original = ($f_name) ? urlencode($data[$f_name]) : urlencode($data[$f_file]);

	if (!$original){
		header("Content-type: text/html; charset=UTF-8");
		alert("파일 정보가 존재하지 않습니다.");
	}

	$filepath = $tgt = $_cfg['web_home'].$_cfg['data_dir']."/$f_dir/".$src;
	$filepath = $filepath;
	if (!is_file($filepath) || !file_exists($filepath)){
		header("Content-type: text/html; charset=UTF-8");
		alert("파일이 존재하지 않습니다.");
	}



}else{
	header("Content-type: text/html; charset=UTF-8");
    alert("잘못된 접근입니다.");
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