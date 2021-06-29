<?
function makePage($paging_data, $query = '' )
{
	global $_cfg;

	$p_str = ($paging_data['page_name']) ? "_".$paging_data['page_name'] : "";
	$str = '<div class="paginate">';

	if($paging_data["enable_prev".$p_str]){
		$str .= '<a href="'.$_SERVER['PHP_SELF'].'?'.$paging_data["start_link".$p_str].$query.'" class="dir">&laquo;</a><a href="'.$_SERVER['PHP_SELF'].'?'.$paging_data["prev_link".$p_str].$query.'" class="dir">&lsaquo;</a>';
	}


	foreach($paging_data["paging".$p_str] as $k => $v){
		if($v['page_num']){
			if($v['page_num'] == $paging_data["page".$p_str]){
				$str .= '<strong>'.$v['page_num'].'</strong>';
			}else{
				$str .= '<a href="'.$_SERVER['PHP_SELF'].'?'.$v['page_link'].$query.'">'.$v['page_num'].'</a>';
			}
		}
	}

	if($paging_data["enable_next".$p_str]){
		$str .= '<a href="'.$_SERVER['PHP_SELF'].'?'.$paging_data["next_link".$p_str].$query.'" class="dir">&rsaquo;</a><a href="'.$_SERVER['PHP_SELF'].'?'.$paging_data["end_link".$p_str].$query.'" class="dir">&raquo;</a>';
	}

	$str .= '</div>';
	return $str;
}
?>