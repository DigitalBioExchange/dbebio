<?php
function custom_count($arr)
{
	if(is_array($arr)){
		return count($arr);
	}else{
		return false;
	}
}
?>