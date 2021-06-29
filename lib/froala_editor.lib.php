<?
function cheditor1($id, $width='100%', $height='250px', $theme = "", $langs = "ko")
{
	global $_cfg;
	//테마 css
	$str = "";
	if(in_array($theme, array("dark", "gray", "red", "royal"))){
		$str .= '<link href="/froala_editor/css/themes/'.$theme.'.css" rel="stylesheet" type="text/css" />
		';
		$str .= '<script type="text/javascript">
		var froala_editor_theme_'.$id.' = "'.$theme.'";
		</script>
		';
	}else{
		$str .= '<script type="text/javascript">
		var froala_editor_theme_'.$id.' = "";
		</script>
		';
	}
	// 언어스크립트
	//$langs = (get_txt_from_data($_cfg['skin']['sk_language'], $lang)) ? $lang : $lang_code; 
	//$langs = 'ko';
	$str .= '<script type="text/javascript" src="/froala_editor/js/langs/'.$langs.'.js"></script>
	';
	$str .= '<script type="text/javascript">
	var froala_editor_lang_'.$id.' = "'.$langs.'";
	</script>
	';
	//크기 스크립트
	$str .= '<script type="text/javascript">
	var froala_editor_width_'.$id.' = "'.$width.'";
	var froala_editor_height_'.$id.' = "'.$height.'";
	</script>
	';
	return $str;
}


function cheditor2($id, $content='')
{
	global $lang_code, $_cfg;
    $str = "
    <textarea name='{$id}' id='{$id}' style='display:none;'></textarea>
    <div id='froala_editor_{$id}'>{$content}</div>";
	$str .= "<script type='text/javascript'>
	  $(function(){
		if(froala_editor_lang_{$id} == 'ko'){
			var font_list = {
			  '맑은 고딕': '맑은 고딕',
			  '돋움': '돋움',
			  '굴림': '굴림',
			  '바탕': '바탕',
			  '궁서': '궁서',
			  'Arial': 'Arial',
			  'Comic Sans MS': 'Comic Sans MS',
			  'Courier New': 'Courier New',
			  'Georgia': 'Georgia',
			  'Lucida Sans Unicode': 'Lucida Sans Unicode',
			  'Tahoma': 'Tahoma',
			  'Times New Roman': 'Times New Roman',
			  'Verdana': 'Verdana'
			};
		}else{
			var font_list = {
			  'Arial': 'Arial',
			  'Comic Sans MS': 'Comic Sans MS',
			  'Courier New': 'Courier New',
			  'Georgia': 'Georgia',
			  'Lucida Sans Unicode': 'Lucida Sans Unicode',
			  'Tahoma': 'Tahoma',
			  'Times New Roman': 'Times New Roman',
			  'Verdana': 'Verdana'
			};
		}


		$('#froala_editor_{$id}').editable({
			theme: froala_editor_theme_{$id},
			inlineMode: false, 
			language: froala_editor_lang_{$id},
			width: froala_editor_width_{$id},
			height: froala_editor_height_{$id},
			imageUploadParam: 'editor_img',
			imageUploadURL: '/inc/froala_upload_image.php',
			imageDeleteURL: '/inc/froala_delete_image.php',
			tabSpaces: true,
			fontList: font_list,
			buttons: ['bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', 'fontFamily', 'fontSize', 'color', 'formatBlock', 'blockStyle', 'align', 'insertOrderedList', 'insertUnorderedList', 'outdent', 'indent', 'selectAll', 'removeFormat', 'undo', 'redo', 'createLink', 'insertImage', 'insertVideo', 'insertHorizontalRule', 'table', 'html', 'fullscreen']


		})
		  // Catch image remove
		  .on('editable.afterRemoveImage', function (e, editor, \$img) {
			// Set the image source to the image delete params.
			editor.options.imageDeleteParams = {src: \$img.attr('src')};

			// Make the delete request.
			editor.deleteImage(\$img);
		  })
		
		;
		$('#froala_editor_{$id}').find('a').each(function(){
			if($(this).html() == 'Unlicensed Froala Editor'){
				$(this).hide();
			}
		});

		$('#froala_editor_{$id}').editable({key: 'ikaqljcmB-11G5hcA3eB-16=='});

		$('#froala_editor_{$id}').ENTER_BR
      });
	  </script>
	";
	return $str;
}
 
function cheditor3($id, $msg = '')
{
	global $lang_code, $_cfg;
	$str = "$('#{$id}').html($('#froala_editor_{$id}').editable('getHTML', false, false));";

	if($msg)$str .= "
	if ($('#{$id}')) {
			if ($('#froala_editor_{$id}').editable('getHTML', false, false) == '') { 
					alert('{$msg}'); 
					return false;
			}
	}
	";
    return $str;
}

function cheditor4($id)
{
	global $lang_code, $_cfg;
	$str = "$('#froala_editor_{$id}').editable('getHTML', false, false)";

    return $str;
}

?>
