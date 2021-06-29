<?
function printImgSwfFile($type, $file, $alt ,$w, $h, $link="", $target="", $class="", $style="")
{
	$w = ($w) ? $w : 100;
	$h = ($h) ? $h : 50;

	if($type == "img" || $type == "jpg" || $type == "gif"){
		$target_str = ($target) ? ' target = "'.$target.'" ' : '';
		if($link){
			$str = '<a href="'.$link.'" '.$target_str.' title="'.get_text($alt).'">';
		}

		$str .= '<img src="'.$file.'" width="'.$w.'" height="'.$h.'" alt="'.get_text($alt).'" class="'.$class.'" style="'.$style.'">';
		if($link){
			$str .= '</a>';
		}
	}else if($type == "swf"){
		$str .= '
		<object width="'.$w.'" height="'.$h.'" title="'.get_text($alt).'" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000">
		  <param value="'.$file.'" name="movie" />
		  <param value="high" name="quality" />
		  <param value="transparent" name="wmode" />
		  <param value="9.0.45.0" name="swfversion" />

		<!--[if !IE]>-->
		 <object width="'.$w.'" height="'.$h.'" data="'.$file.'" type="application/x-shockwave-flash">
		 <!--<![endif]-->
		  <param value="high" name="quality" />
		  <param value="transparent" name="wmode" />
		  <param value="9.0.45.0" name="swfversion" />
		  <p>이 컨텐츠는 플래시(flash)로 제작되었습니다. 이 컨텐츠를 보려면 <a href="http://www.adobe.com/kr/products/flashplayer/" target="_blank" title="새창에서 열림">Flash Player</a>(무료)가 필요합니다.</p>
		  <p>플래시 컨텐츠에 대한 대체 텍스트</p>
		 <!--[if !IE]>-->
		 </object>
		 <!--<![endif]-->
		 </object>
		';
	}
	return $str;
}
?>