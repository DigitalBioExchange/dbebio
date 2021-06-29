<?
function cheditor1($id, $width='100%', $height='250px')
{
	global $is_from_super;
	$str = "
	<script type='text/javascript'>
	var ed_{$id} = new cheditor('ed_{$id}');
	ed_{$id}.config.editorHeight = '{$height}';
	ed_{$id}.config.editorWidth = '{$width}';
	ed_{$id}.inputForm = 'tx_{$id}';
	";
	if($is_from_super){
		$str .= "
	ed_{$id}.config.imgReSizeFull = true;
		";
	}
	$str .= "</script>";
	return $str;
}


function cheditor2($id, $content='')
{
    return "
    <textarea name='{$id}' id='tx_{$id}' style='display:none;'>{$content}</textarea>
    <script type='text/javascript'>
    ed_{$id}.run();
	ed_{$id}.cheditor.toolbarWrapper.style.display = 'block';
	window.onload = function() {
		ed_{$id}.cheditor.toolbarWrapper.style.display = 'block';
	};
    </script>";
}
 
function cheditor3($id, $msg = '')
{
	$str = "document.getElementById('tx_{$id}').value = ed_{$id}.outputBodyHTML();";

	if($msg)$str .= "
	if (document.getElementById('tx_{$id}')) {
			if (!ed_{$id}.outputBodyHTML()) { 
					alert('{$msg}'); 
					ed_{$id}.returnFalse();
					return false;
			}
	}
	";
    return $str;
}
function cheditor4($id)
{
	global $lang_code, $_cfg;
	$str = "ed_{$id}.outputBodyHTML()";

    return $str;
}
?>
