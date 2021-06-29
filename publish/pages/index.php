<?
// /web/018.html 파일에 파일명 써 놓는 규칙 설명했음

function custom_count($arr)
{
	if(is_array($arr)){
		return count($arr);
	}else{
		return false;
	}
}
function array_sort($array, $on, $order=SORT_ASC)
{
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
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
            $new_array[] = $array[$k];
        }
    }

    return $new_array;
}
// 파일 확장자 구하기
function get_file_ext($filename)
{
	global $_cfg;
	$arr = explode(".", $filename);
	$ext = end($arr);
	return $ext;
}
function p_arr($arr){
	if(is_array($arr)){
		echo "<pre>";
		print_r($arr);
		echo "</pre>";
	}else{
		echo "not array : $arr </br>";
	}
}

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
					$temp['txt'] = substr($folder_name, 1)."/".$file_str;
					$rslt[] = $temp;
				}
			}else if($file_str!="." && $file_str!=".." && is_dir($folder_name."/".$file_str) && $file_str != "thumb" && $file_str != "inc"){
				$tmp_list = make_file_list($folder_name."/".$file_str, $arg);
				if(custom_count($tmp_list) > 0){
					foreach($tmp_list as $k => $v){
						$rslt[] = $v;
					}
				}
			}
		}
		closedir($dir_obj);
	}

	return $rslt;
}

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

$platform_list = make_dir_list("./");
//p_arr($platform_list);exit;
if($_GET['platform'] == "" && $platform_list[0]['val'] != ""){
	$_GET['platform'] = $platform_list[0]['val'];
}

$file_list = array_sort(make_file_list("./".$_GET['platform']), 'txt', SORT_ASC);;
//p_arr($file_list);exit;

if(custom_count($file_list) > 0){
	$cnt = 0;
	foreach($file_list as $k => $v){
		$file_data = file_get_contents(".".$v['txt']);
		unset($match);
		preg_match_all('/<\!\-\-\!(.*)\!\-\->/isU',$file_data, $match);
		if($match[1][0] != ""){
			$file_list[$cnt]['file_name'] = $match[1][0];
		}else{
			$file_list[$cnt]['file_name'] = $v['val'];
		}
		$cnt++;
	}
}

?>
<!DOCTYPE HTML><html lang="ko-KR">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
	<title>루소프트 마크업 리스트</title>
	<?if($platform="mobile"){?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<?}?>
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<meta name="author" content="" />
		<script type="text/javascript" src="http://design.lusoft.co.kr/publish/js/jquery-1.11.3.min.js"></script>
</head>

<body>
<!-- *wrapper* 시작  -->
<div id="wrapper">

	<div class="platform">
		<ul>
			<?foreach($platform_list as $k => $v){?>
			<li class="<?if($_GET['platform'] == $v['val']){?>on<?}?>"><a href="./index.php?platform=<?=$v['val']?>" ><?=$v['val']?></a></li>
			<?}?>
		</ul>
	</div>

	<?if(custom_count($file_list) > 0){?>
	
	<div id="header"><h1>루소프트 마크업 리스트</h1></div>
	
	<!-- <div class="notice-info">
		<ul>
			<li>emty값, 검색결과 없을 때 등, 관련 페이지 없음, 일부 누락페이지 있음(기획서불확실)</li>
		</ul>
	</div> -->
	
	<table class="markup-list">
		<tbody>
			<tr>
				<th colspan="2">Markup List</th>
			</tr>
			<?foreach($file_list as $k => $v){?>
			<tr>
				<td><a href=".<?=$v['txt']?>"><?=$v['file_name']?></a></td>
				<td><a href=".<?=$v['txt']?>"><?=$v['txt']?></a></td>
			</tr>
			<?}?>
		</tbody>
	</table>
	<?}?>
	

</div>
<!-- *wrapper* 끝  -->

</body>
	<style>
	*{-webkit-text-size-adjust:none;}
		html, body, div, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre, abbr, address, cite, code, del, dfn, em, img, ins, ul, li, fieldset, form, label, table, caption, tbody, tfoot, figure, footer, header, hgroup, menu, nav, section, summary, time, mark, audio, video{margin:0; padding:0; outline:0; font-family:YoonPro; font-size:13px; color:#555; }
		body{line-height:1.2em; border:0;}
		strong, em, span, wbr, a{display:inline-block;}
		span{margin:0; padding:0; outline:0;}
		hr{display:none}
		ol, ul, ,dl, dt, dd{list-style:none outside none; padding:0; margin:0;}
		fieldset, img, table, caption, tbody, tfoot, thead, tr, th, td, button, hr{border:0;}
		address, caption, cite, code, dfn, em, strong, th, var{font-style: normal;}
		img{vertical-align:middle;}
		a{cursor:pointer; text-decoration:none; color:#666;}
		table{border-collapse:collapse;}
		
		
		
		@media screen and (min-width: 1920px) {
		#wrapper{ width:1080px; height:100%; margin:0 auto 120px;}
		}
		
		@media screen and (max-width: 769px) {
		#wrapper{ width:100%; height:100%; margin:0 auto 8px; padding:16px; box-sizing:border-box;}
		}
		
		#header{  width:100%; margin:32px 0 16px;}
		#header>h1{ display:inline-block; font-size:24px; color:#161616; }
		
		div.notice-info { border:1px solid #e8d893; background:#fbf7e7; line-height:1.8; padding:12px 32px; margin-bottom:10px; }

		table{width:100%;table-layout: fixed}
		table.markup-list th,table.markup-list td{border:1px solid #d6d6d6;}
		table.markup-list tr:first-child td{text-align:left; background:#f6f6f6; font-weight:bold; border-bottom:1px solid #d6d6d6; height:24px; color:#868686; font-size:11px;}

		/*검수*/
		table.markup-list th,table.markup-list td a { background: #ffcc33; padding: 4px 8px;}
		table.markup-list th,table.markup-list td a:visited { background: #fff; }
		
		table.markup-list th{padding:16px 8px 8px; text-align:left; border-bottom:2px solid #e80032; !important; border-left:1px solid #fff; border-right:1px solid #fff;}
		
		table.markup-list th.depth2{padding: 2px 18px; text-align:left; border-bottom:1px solid #333 !important; height:30px; font-size: 12px; background-color: #fff}
		
		table.markup-list td{padding:3px 8px; text-overflow:ellipsis; white-space:nowrap; word-wrap:normal; overflow:hidden;}
		table.markup-list td:first-child{text-align:left;}
		table.markup-list td:last-child{color:#888; font-size:12px;}
		table.markup-list td > input{width:20px; height:20px;}
		table.markup-list td>a.btn_link{display:block; width:100%; height:24px; line-height:24px; text-align:left; color:#fff; font-size:11px; font-weight:bold; vertical-align:middle;background-color:#fff; color:#e80032; border:1px solid #e80032; transition:all 0.5s;}
		table.markup-list td>a.btn_link:hover{ background-color:#e80032; color:#fff;}
		table.markup-list tr:hover>td{background-color:#f9f9f9;}
		table.markup-list td.ing{text-align:center; font-size:12;}
		table.markup-list td.end{text-align:center; font-size:12; font-weight:bold; background-color:#b8bbbf; color:#161616;}
		
		.platform { width:100%; height:40px; margin-top:10px; }
		.plaform > ul { width:100%; height:100%; display:inline-block;}
		.platform > ul > li {  list-style:none; float:left; width:50%; height:100%; text-align:center; }
		.platform > ul > li > a { width:100%; height:100%; display:block; line-height:40px; font-size:13px; background:#f9f9f9; border:1px solid #161616; color:#161616; transition:all 0.5s;}
		.platform > ul > li.on > a { position: relative; background: #161616; color:#fff;}
		.platform > ul > li.on > a:after { content: ''; display: inline-block; position: absolute; bottom: -4px; left: 50%; margin-left: -4px; width: 8px; height: 8px; background: #161616; transform: rotate(45deg); }
		.detail { color:#4a96fd; font-size:12px; font-style:italic;}
	</style>
</html>