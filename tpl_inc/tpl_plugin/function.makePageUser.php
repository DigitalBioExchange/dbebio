<?
function makePageUser($paging_data, $query = '')
{
	global $_cfg, $user_agent;

	
	$p_str = ($paging_data['page_name']) ? "_".$paging_data['page_name'] : "";

	if($user_agent == "web"){
		$str = '<ul>';

		if($paging_data["enable_prev".$p_str]){
			$str .= '<li class="dir start"><a href="'.$_SERVER['PHP_SELF'].'?'.$paging_data["start_link".$p_str].$query.'" ></a></li>';
			$str .= '<li class="dir prev"><a href="'.$_SERVER['PHP_SELF'].'?'.$paging_data["prev_link".$p_str].$query.'"></a></li>';
		}

		$n = 0;
		if (!empty($paging_data)) {
			foreach($paging_data["paging".$p_str] as $k => $v){
				if($v['page_num']){
					if($v['page_num'] == $paging_data["page".$p_str]){
						$str .= '<li class="on"><a href="javascript:;">'.$v['page_num'].'</a></li>';
					}else{
						$str .= '<li><a href="'.$_SERVER['PHP_SELF'].'?'.$v['page_link'].$query.'">'.$v['page_num'].'</a></li>';
					}
				}
				$n++;
				if($n < custom_count($paging_data["paging".$p_str])){
					// $str .= "ㆍ";
				}
			}
		}

		if($paging_data["enable_next".$p_str]){
			$str .= '<li class="dir next"><a href="'.$_SERVER['PHP_SELF'].'?'.$paging_data["next_link".$p_str].$query.'" ></a></li>';
			$str .= '<li class="dir end"><a href="'.$_SERVER['PHP_SELF'].'?'.$paging_data["end_link".$p_str].$query.'" ></a></li>';
		}

		$str .= '</ul>';
	}else{
		$str = '<ul>';

		if($paging_data["enable_prev".$p_str]){
			$str .= '<li class="dir start"><a href="'.$_SERVER['PHP_SELF'].'?'.$paging_data["start_link".$p_str].$query.'" ></a></li>';
			$str .= '<li class="dir prev"><a href="'.$_SERVER['PHP_SELF'].'?'.$paging_data["prev_link".$p_str].$query.'"></a></li>';
		}

		$n = 0;
		if (!empty($paging_data)) {
			foreach($paging_data["paging".$p_str] as $k => $v){
				if($v['page_num']){
					if($v['page_num'] == $paging_data["page".$p_str]){
						$str .= '<li class="on"><a href="javascript:;">'.$v['page_num'].'</a></li>';
					}else{
						$str .= '<li><a href="'.$_SERVER['PHP_SELF'].'?'.$v['page_link'].$query.'">'.$v['page_num'].'</a></li>';
					}
				}
				$n++;
				if($n < custom_count($paging_data["paging".$p_str])){
					// $str .= "ㆍ";
				}
			}
		}

		if($paging_data["enable_next".$p_str]){
			$str .= '<li class="dir next"><a href="'.$_SERVER['PHP_SELF'].'?'.$paging_data["next_link".$p_str].$query.'" ></a></li>';
			$str .= '<li class="dir end"><a href="'.$_SERVER['PHP_SELF'].'?'.$paging_data["end_link".$p_str].$query.'" ></a></li>';
		}

		$str .= '</ul>';

	}
	return $str;
}
?>