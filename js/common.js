function number_format(number, decimals, dec_point, thousands_sep) {
    // Formats a number with grouped thousands  
    // 
    // version: 1006.1915
    // discuss at: http://phpjs.org/functions/number_format    // +   original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +     bugfix by: Michael White (http://getsprink.com)
    // +     bugfix by: Benjamin Lupton
    // +     bugfix by: Allan Jensen (http://www.winternet.no)    // +    revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
    // +     bugfix by: Howard Yeend
    // +    revised by: Luke Smith (http://lucassmith.name)
    // +     bugfix by: Diogo Resende
    // +     bugfix by: Rival    // +      input by: Kheang Hok Chin (http://www.distantia.ca/)
    // +   improved by: davook
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +      input by: Jay Klehr
    // +   improved by: Brett Zamir (http://brett-zamir.me)    // +      input by: Amir Habibi (http://www.residence-mixte.com/)
    // +     bugfix by: Brett Zamir (http://brett-zamir.me)
    // +   improved by: Theriault
    // *     example 1: number_format(1234.56);
    // *     returns 1: '1,235'    // *     example 2: number_format(1234.56, 2, ',', ' ');
    // *     returns 2: '1 234,56'
    // *     example 3: number_format(1234.5678, 2, '.', '');
    // *     returns 3: '1234.57'
    // *     example 4: number_format(67, 2, ',', '.');    // *     returns 4: '67,00'
    // *     example 5: number_format(1000);
    // *     returns 5: '1,000'
    // *     example 6: number_format(67.311, 2);
    // *     returns 6: '67.31'    // *     example 7: number_format(1000.55, 1);
    // *     returns 7: '1,000.6'
    // *     example 8: number_format(67000, 5, ',', '.');
    // *     returns 8: '67.000,00000'
    // *     example 9: number_format(0.9, 0);    // *     returns 9: '1'
    // *    example 10: number_format('1.20', 2);
    // *    returns 10: '1.20'
    // *    example 11: number_format('1.20', 4);
    // *    returns 11: '1.2000'    // *    example 12: number_format('1.2000', 3);
    // *    returns 12: '1.200'
    var n = !isFinite(+number) ? 0 : +number, 
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }    return s.join(dec);
}

function htmlspecialchars (string, quote_style, charset, double_encode) {
    // Convert special characters to HTML entities  
    // 
    // version: 1006.1915
    // discuss at: http://phpjs.org/functions/htmlspecialchars    // +   original by: Mirek Slugen
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   bugfixed by: Nathan
    // +   bugfixed by: Arno
    // +    revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)    // +    bugfixed by: Brett Zamir (http://brett-zamir.me)
    // +      input by: Ratheous
    // +      input by: Mailfaker (http://www.weedem.fr/)
    // +      reimplemented by: Brett Zamir (http://brett-zamir.me)
    // +      input by: felix    // +    bugfixed by: Brett Zamir (http://brett-zamir.me)
    // %        note 1: charset argument not supported
    // *     example 1: htmlspecialchars("<a href='test'>Test</a>", 'ENT_QUOTES');
    // *     returns 1: '&lt;a href=&#039;test&#039;&gt;Test&lt;/a&gt;'
    // *     example 2: htmlspecialchars("ab\"c'd", ['ENT_NOQUOTES', 'ENT_QUOTES']);    // *     returns 2: 'ab"c&#039;d'
    // *     example 3: htmlspecialchars("my "&entity;" is still here", null, null, false);
    // *     returns 3: 'my &quot;&entity;&quot; is still here'
    var optTemp = 0, i = 0, noquotes= false;
    if (typeof quote_style === 'undefined' || quote_style === null) {        quote_style = 2;
    }
    string = string.toString();
    if (double_encode !== false) { // Put this first to avoid double-encoding
        string = string.replace(/&/g, '&amp;');    }
    string = string.replace(/</g, '&lt;').replace(/>/g, '&gt;');
 
    var OPTS = {
        'ENT_NOQUOTES': 0,        'ENT_HTML_QUOTE_SINGLE' : 1,
        'ENT_HTML_QUOTE_DOUBLE' : 2,
        'ENT_COMPAT': 2,
        'ENT_QUOTES': 3,
        'ENT_IGNORE' : 4    };
    if (quote_style === 0) {
        noquotes = true;
    }
    if (typeof quote_style !== 'number') { // Allow for a single string or an array of string flags        quote_style = [].concat(quote_style);
        for (i=0; i < quote_style.length; i++) {
            // Resolve string input to bitwise e.g. 'PATHINFO_EXTENSION' becomes 4
            if (OPTS[quote_style[i]] === 0) {
                noquotes = true;            }
            else if (OPTS[quote_style[i]]) {
                optTemp = optTemp | OPTS[quote_style[i]];
            }
        }        quote_style = optTemp;
    }
    if (quote_style & OPTS.ENT_HTML_QUOTE_SINGLE) {
        string = string.replace(/'/g, '&#039;');
    }    if (!noquotes) {
        string = string.replace(/"/g, '&quot;');
    }
 
    return string;
}

function array_search (needle, haystack, argStrict) {
    // Searches the array for a given value and returns the corresponding key if successful  
    // 
    // version: 1006.1915
    // discuss at: http://phpjs.org/functions/array_search    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +      input by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // *     example 1: array_search('zonneveld', {firstname: 'kevin', middle: 'van', surname: 'zonneveld'});
    // *     returns 1: 'surname' 
    var strict = !!argStrict;
    var key = '';
 
    for (key in haystack) {        if ((strict && haystack[key] === needle) || (!strict && haystack[key] == needle)) {
            return key;
        }
    }
     return false;
}

function in_array (needle, haystack, argStrict) {
    // Checks if the given value exists in the array  
    // 
    // version: 1004.2314
    // discuss at: http://phpjs.org/functions/in_array    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: vlado houba
    // +   input by: Billy
    // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
    // *     example 1: in_array('van', ['Kevin', 'van', 'Zonneveld']);    // *     returns 1: true
    // *     example 2: in_array('vlado', {0: 'Kevin', vlado: 'van', 1: 'Zonneveld'});
    // *     returns 2: false
    // *     example 3: in_array(1, ['1', '2', '3']);
    // *     returns 3: true    // *     example 3: in_array(1, ['1', '2', '3'], false);
    // *     returns 3: true
    // *     example 4: in_array(1, ['1', '2', '3'], true);
    // *     returns 4: false
    var key = '', strict = !!argStrict; 
    if (strict) {
        for (key in haystack) {
            if (haystack[key] === needle) {
                return true;            }
        }
    } else {
        for (key in haystack) {
            if (haystack[key] == needle) {                return true;
            }
        }
    }
     return false;
}

function get_int(arg)
{
	var tmp = arg.replace(/[^0-9]/g, '');
	if(tmp == "") tmp = 0;
	return parseInt(tmp, 10);
}

function get_float(arg, arg2)
{
	var tmp = arg.replace(/[^0-9\.]/g, '');
	if(tmp == "") tmp = 0;
	var p = Math.pow(10, arg2);
	tmp = tmp * p;
	tmp = parseInt(tmp, 10);
	tmp = tmp / p;

	return tmp;
}

// 라디오 버튼 값 체크
function find_radio_value(frv_name)
{
	var obj = document.getElementsByName(frv_name);
	for (var i = 0; i < obj.length; i++) {
		if(obj[i].checked == true) {
			return obj[i].value;
			break;
		}
	} 
}

function dis_radio_value_all(frv_name)
{
	var obj = document.getElementsByName(frv_name);
	for (var i = 0; i < obj.length; i++) {
		if(obj[i].checked == true) {
			obj[i].checked = false;
		}

		obj[i].disabled = true;
	} 
}

function en_radio_value_all(frv_name)
{
	var obj = document.getElementsByName(frv_name);
	for (var i = 0; i < obj.length; i++) {
		obj[i].disabled = false;
	} 
}

function radio_value_dis(frv_name, val)
{
	var obj = document.getElementsByName(frv_name);
	for (var i = 0; i < obj.length; i++) {
		if(obj[i].value == val) {
			obj[i].checked = true;
			obj[i].disabled = false;
		}else{
			obj[i].checked = false;
			obj[i].disabled = true;
		}
	} 
}

function radio_value_en(frv_name, val)
{
	var obj = document.getElementsByName(frv_name);
	var cnt = 0;
	for (var i = 0; i < obj.length; i++) {
		if(obj[i].value == val) {
			obj[i].checked = true;
			cnt++;
		}else{
			obj[i].checked = false;
		}
		obj[i].disabled = false;
	}
	if(cnt == 0 && obj.length > 0){
		obj[0].checked = true;
	}
}

function change_select_value(obj, val)
{
	var cnt = 0;
	for (var i = 0; i < obj.length; i++) {
		if(obj.options[i].value == val) {
			obj.options[i].selected = true;
			cnt++;
		}else{
			obj.options[i].selected = false;
		}
	}
	if(cnt == 0 && obj.length > 0){
		obj.options[0].selected = true;
	}
}

function cut_str_value(obj, arg)
{
	var val = obj.value;
	var len = 0;
	var c;
	var tmp = "";
	var after_val = "";
	var ent = 0;

	for(i=0;i<val.length;i++){
		tmp = val.substring(i, i + 1);
		c = val.substring(i, i + 1).charCodeAt(0);
		len ++;
		if(c <= 0 || c > 255) len++;  //2바이트문자(한글등)일경우 1증가
		if(c == 13)ent++;
		if(len <= arg && ent < 3){
			after_val += tmp;
		}else{
			alert(_lang[lang_code]['common']['msg01'].replace('[thisnum]', arg));
			obj.value = after_val;
			return;
		}
	}
}

// 새 창
function popup_window(url, winname, opt)
{
	window.open(url, winname, opt);
}


// 로그인 체크
function login_ck(f)
{
	if(!f.id.value){
		alert(_lang[lang_code]['common']['msg02']);
		f.id.focus();
		return;
	}
	if(!f.pw.value){
		alert(_lang[lang_code]['common']['msg03']);
		f.pw.focus();
		return;
	}
	if(f.id.value && f.pw.value){
		//f.action = "login_process.php";
		f.submit();
	}
}


function key_press(e, f)
{
	var event = e || window.event; 
	if(event.keyCode==13) {
		login_ck(f);
	}
}

function key_press_login(e, f)
{
	var event = e || window.event; 
	if(event.keyCode==13) {
		login_ck_user(f);
	}
}

function key_press1(e, arg1, arg2, arg3, arg4, arg5, arg6, arg7)
{
	var event = e || window.event; 
	if(event.keyCode==13) {
		ajax_start(arg1, arg2, arg3, arg4, arg5, arg6, arg7);
	}
}


function key_press2(e, f)
{
	var event = e || window.event; 
	if(event.keyCode==13) {
		login_submit_left(f);
	}
}

function key_press3(e, f)
{
	var event = e || window.event; 
	if(event.keyCode==13) {
		login_submit(f);
	}
}

// 로그인 체크 부분 끝


// 리스트에서전체 선택
function selectAll(f, names)
{ 
	if(!f.allchk.checked) { 
		var check_nums = f.elements.length;

		for(var j=0; j< check_nums ; j++) {
			if( f.elements[j].name == names) { 
			 var checkbox_obj = eval("f.elements[" + j + "]");
				checkbox_obj.checked = false;
			}
		}
	}else{
		var check_nums = f.elements.length;

		for(var j=0; j< check_nums ; j++) {
			if( f.elements[j].name == names) { 
			 var checkbox_obj = eval("f.elements[" + j + "]");
				checkbox_obj.checked = true;
			}
		}
	}
}

// 리스트에서전체 선택
function selectAll2(f, names, obj)
{ 
	if(!obj.checked) { 
		var check_nums = f.elements.length;

		for(var j=0; j< check_nums ; j++) {
			if( f.elements[j].name == names) { 
			 var checkbox_obj = eval("f.elements[" + j + "]");
				checkbox_obj.checked = false;
			}
		}
	}else{
		var check_nums = f.elements.length;

		for(var j=0; j< check_nums ; j++) {
			if( f.elements[j].name == names) { 
			 var checkbox_obj = eval("f.elements[" + j + "]");
				checkbox_obj.checked = true;
			}
		}
	}
}

// 선택된 갯수
function check_select_all_cnt(f, names)
{
	var cnt = 0;
	var check_nums = f.elements.length;

	for(var j=0; j< check_nums ; j++) {
		if( f.elements[j].name == names) { 
			var checkbox_obj = eval("f.elements[" + j + "]");
			if(checkbox_obj.checked == true){
				cnt++;
			}
		}
	}
	return cnt;
}

function key_press_search(e, sca, stx, url, param)
{
	var event = e || window.event; 
	if(event.keyCode==13) {
		search_ok(sca, stx, url, param);
	}
}



function search_ok(sca, stx, url, param)
{
	location.href = url + "?sca=" + sca + "&stx=" + encodeURIComponent(stx) + param;
}

function go_select(name, val, url, param)
{
	location.href = url + "?" + name + "=" + val + param;
}

function all_delete(f, name, url)
{
	var su = 0;
	var check_nums = f.elements.length;

	for(var j=0; j< check_nums ; j++) {
		if( f.elements[j].name == name) { 
		 var checkbox_obj = eval("f.elements[" + j + "]");
			if(checkbox_obj.checked == true){
				su++;
			}
		}
	}
	if(su == 0){
		alert(_lang[lang_code]['common']['msg04']);
	}else{
		if(confirm(_lang[lang_code]['common']['msg05'])){
			f.mode.value = "delete";
			f.action = url;
			f.submit();
		}
	}
}

function all_change(f, name, url, field, val, msg)
{
	var su = 0;
	var check_nums = f.elements.length;
	if(msg == undefined){
		msg = _lang[lang_code]['common']['msg06'];
	}

	for(var j=0; j< check_nums ; j++) {
		if( f.elements[j].name == name) { 
		 var checkbox_obj = eval("f.elements[" + j + "]");
			if(checkbox_obj.checked == true){
				su++;
			}
		}
	}
	if(su == 0){
		alert(_lang[lang_code]['common']['msg04']);
	}else{
		if(confirm(msg)){
			f.mode.value = "change";
			f.change_field.value = field;
			f.change_val.value = val;
			f.action = url;
			f.submit();
		}
	}
}

function all_change2(f, name, url, val, msg)
{
	var su = 0;
	var check_nums = f.elements.length;

	for(var j=0; j< check_nums ; j++) {
		if( f.elements[j].name == name) { 
		 var checkbox_obj = eval("f.elements[" + j + "]");
			if(checkbox_obj.checked == true){
				su++;
			}
		}
	}
	if(su == 0){
		alert(_lang[lang_code]['common']['msg04']);
	}else{
		if(confirm(msg)){
			f.mode.value = "change";
			f.change_val.value = val;
			f.action = url;
			f.submit();
		}
	}
}


function fill_address(zip1, zip2, addr, pre)
{
 	$('#' + pre + '_zip1').val(zip1);
	$('#' + pre + '_zip2').val(zip2);
	$('#' + pre + '_addr1').val(addr);
}

function fill_address_new(zip1, zip2, addr, addr2, pre)
{
 	$('#' + pre + '_zip1').val(zip1);
	$('#' + pre + '_zip2').val(zip2);
	$('#' + pre + '_addr1').val(addr);
	$('#' + pre + '_addr2').val(addr2);
}

/* 로긴 안했을 때 함수  */
function goto_login(arg1)
{
	if(!arg1){
		alert(_lang[lang_code]['common']['msg07']);
	}else if(arg1 =='alert'){
		alert(_lang[lang_code]['common']['msg07']);
	}
}


/*  인풋창에 특정글자 포커스시 컨트롤하기  */
function input_focus_control(obj, str)
{
	if(obj && obj.value == str){
		obj.value = "";
	}
}

/*  인풋창에 특정글자 블루어시 컨트롤하기  */
function input_blur_control(obj, str)
{
	if(obj && obj.value == ""){
		obj.value = str;
	}
}


//준비중 앨럿
function alert_ready()
{
	alert(_lang[lang_code]['common']['msg08']);
}


function open_user_new_window(arg, arg2)
{
	if(arg2){
		var ttt = window.open(arg2 +'&chk_user_id='+arg, 'chk_user_id', '');
	}else{
		var ttt = window.open('/index.php?chk_user_id='+arg, 'chk_user_id', '');
	}
	ttt.focus();
}


function cal_days(day1, day2){
	if(day1 != '' && day2 != ''){
		var day1_arr = day1.split('-');
		var day2_arr = day2.split('-');

		var day2data = new Date(get_int(day2_arr[0]), get_int(day2_arr[1]), get_int(day2_arr[2]));
		var day1data = new Date(get_int(day1_arr[0]), get_int(day1_arr[1]), get_int(day1_arr[2]));
		//xday.setYear = today.getYear;  
		daysAfter = (day2data.getTime() - day1data.getTime()) / (1000*60*60*24);
		daysAfter = Math.round(daysAfter);
		return daysAfter + 1;
	}else{
		return '';
	}
}

// HTML 로 넘어온 <img ... > 태그의 폭이 테이블폭보다 크다면 테이블폭을 적용한다.
function resizeBoardImage(imageWidth, obj_id_str) {
	window.onload = function(){
		if(obj_id_str){
			var obj_id_arr = obj_id_str.split('|');
			for(var i=0;i<obj_id_arr.length;i++){
				var obj_id = obj_id_arr[i];
				if ($('#'+obj_id)) {
					if(get_int(imageWidth) != imageWidth){
						var wrap_width = $('#'+obj_id).width();
						//alert($('#'+obj_id).width());
						$('img', $('#'+obj_id)).each(function ()
							{
								if($(this).width() > wrap_width || 1) {

									// 원래 사이즈를 저장해 놓는다
									var org_width = parseInt($(this).width());
									var org_height = parseInt($(this).height());

									$(this).css('width', imageWidth);
									//alert(imageWidth);

									var imageHeight = parseFloat(org_width / org_height);
									var new_width = parseInt($(this).width());
									$(this).css('height', parseInt(new_width / imageHeight) + 'px');
								}
							}
						);
					}else{
						var imageHeight = 0;
						$('img', $('#'+obj_id)).each(function ()
							{
								// 원래 사이즈를 저장해 놓는다
								var org_width = parseInt($(this).css('width'));
								var org_height = parseInt($(this).css('height'));

								$(this).attr('tmp_width', org_width);
								$(this).attr('tmp_height', org_height);

								// 이미지 폭이 테이블 폭보다 크다면 테이블폭에 맞춘다
							
								if(org_width > imageWidth) {
									imageHeight = parseFloat(org_width / org_height);
									$(this).attr('width', imageWidth);
									$(this).attr('height', parseInt(imageWidth / imageHeight));
									$(this).css('cursor', 'pointer');
									/*
									$(this).click(function() {
										make_big_image_window(this)
									});
									*/

									$(this).css('width', imageWidth + 'px');
									$(this).css('height', parseInt(imageWidth / imageHeight) + 'px');
								}
							}
						);
					}
				}
			}
		}
	}
}

function make_big_image_window(obj)
{
	$('#big_image_wrap').html('<img id="big_img_object" src="" />');

	$('#big_img_object').css('width', $(obj).attr('tmp_width'));
	$('#big_img_object').css('height', $(obj).attr('tmp_height'));

	$('#big_img_object').attr('src', $(obj).attr('src'));
	
	$('#big_img_object').css('cursor', 'pointer');
	$('#big_img_object').click(function() {
						$('#big_image_wrap').hide();
					});

	var left = ($(window).width() - $('#big_image_wrap').outerWidth()) / 2 + $(window).scrollLeft();
	var top = ($(window).height() - $('#big_image_wrap').outerHeight()) / 2 + $(window).scrollTop();

    $('#big_image_wrap').css({margin:0, top : (top > $(window).scrollTop() ? top : $(window).scrollTop()) + 'px', left: (left > 0 ? left : 0)+'px'}); 

	$('#big_image_wrap').show();
}

function strip_tags(input, allowed) {
  // http://kevin.vanzonneveld.net
  // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   improved by: Luke Godfrey
  // +      input by: Pul
  // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   bugfixed by: Onno Marsman
  // +      input by: Alex
  // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +      input by: Marc Palau
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +      input by: Brett Zamir (http://brett-zamir.me)
  // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   bugfixed by: Eric Nagel
  // +      input by: Bobby Drake
  // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   bugfixed by: Tomasz Wesolowski
  // +      input by: Evertjan Garretsen
  // +    revised by: Rafał Kukawski (http://blog.kukawski.pl/)
  // *     example 1: strip_tags('<p>Kevin</p> <br /><b>van</b> <i>Zonneveld</i>', '<i><b>');
  // *     returns 1: 'Kevin <b>van</b> <i>Zonneveld</i>'
  // *     example 2: strip_tags('<p>Kevin <img src="someimage.png" onmouseover="someFunction()">van <i>Zonneveld</i></p>', '<p>');
  // *     returns 2: '<p>Kevin van Zonneveld</p>'
  // *     example 3: strip_tags("<a href='http://kevin.vanzonneveld.net'>Kevin van Zonneveld</a>", "<a>");
  // *     returns 3: '<a href='http://kevin.vanzonneveld.net'>Kevin van Zonneveld</a>'
  // *     example 4: strip_tags('1 < 5 5 > 1');
  // *     returns 4: '1 < 5 5 > 1'
  // *     example 5: strip_tags('1 <br/> 1');
  // *     returns 5: '1  1'
  // *     example 6: strip_tags('1 <br/> 1', '<br>');
  // *     returns 6: '1  1'
  // *     example 7: strip_tags('1 <br/> 1', '<br><br/>');
  // *     returns 7: '1 <br/> 1'
  allowed = (((allowed || "") + "").toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
  var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
    commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
  return input.replace(commentsAndPhpTags, '').replace(tags, function ($0, $1) {
    return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
  });
}

function substr (str, start, len) {
  // Returns part of a string
  //
  // version: 909.322
  // discuss at: http://phpjs.org/functions/substr
  // +     original by: Martijn Wieringa
  // +     bugfixed by: T.Wild
  // +      tweaked by: Onno Marsman
  // +      revised by: Theriault
  // +      improved by: Brett Zamir (http://brett-zamir.me)
  // %    note 1: Handles rare Unicode characters if 'unicode.semantics' ini (PHP6) is set to 'on'
  // *       example 1: substr('abcdef', 0, -1);
  // *       returns 1: 'abcde'
  // *       example 2: substr(2, 0, -6);
  // *       returns 2: false
  // *       example 3: ini_set('unicode.semantics',  'on');
  // *       example 3: substr('a\uD801\uDC00', 0, -1);
  // *       returns 3: 'a'
  // *       example 4: ini_set('unicode.semantics',  'on');
  // *       example 4: substr('a\uD801\uDC00', 0, 2);
  // *       returns 4: 'a\uD801\uDC00'
  // *       example 5: ini_set('unicode.semantics',  'on');
  // *       example 5: substr('a\uD801\uDC00', -1, 1);
  // *       returns 5: '\uD801\uDC00'
  // *       example 6: ini_set('unicode.semantics',  'on');
  // *       example 6: substr('a\uD801\uDC00z\uD801\uDC00', -3, 2);
  // *       returns 6: '\uD801\uDC00z'
  // *       example 7: ini_set('unicode.semantics',  'on');
  // *       example 7: substr('a\uD801\uDC00z\uD801\uDC00', -3, -1)
  // *       returns 7: '\uD801\uDC00z'
  // Add: (?) Use unicode.runtime_encoding (e.g., with string wrapped in "binary" or "Binary" class) to
  // allow access of binary (see file_get_contents()) by: charCodeAt(x) & 0xFF (see https://developer.mozilla.org/En/Using_XMLHttpRequest ) or require conversion first?
  var i = 0,
    allBMP = true,
    es = 0,
    el = 0,
    se = 0,
    ret = '';
  str += '';
  var end = str.length;

  // BEGIN REDUNDANT
  this.php_js = this.php_js || {};
  this.php_js.ini = this.php_js.ini || {};
  // END REDUNDANT
  switch ((this.php_js.ini['unicode.semantics'] && this.php_js.ini['unicode.semantics'].local_value.toLowerCase())) {
  case 'on':
    // Full-blown Unicode including non-Basic-Multilingual-Plane characters
    // strlen()
    for (i = 0; i < str.length; i++) {
      if (/[\uD800-\uDBFF]/.test(str.charAt(i)) && /[\uDC00-\uDFFF]/.test(str.charAt(i + 1))) {
        allBMP = false;
        break;
      }
    }

    if (!allBMP) {
      if (start < 0) {
        for (i = end - 1, es = (start += end); i >= es; i--) {
          if (/[\uDC00-\uDFFF]/.test(str.charAt(i)) && /[\uD800-\uDBFF]/.test(str.charAt(i - 1))) {
            start--;
            es--;
          }
        }
      } else {
        var surrogatePairs = /[\uD800-\uDBFF][\uDC00-\uDFFF]/g;
        while ((surrogatePairs.exec(str)) != null) {
          var li = surrogatePairs.lastIndex;
          if (li - 2 < start) {
            start++;
          } else {
            break;
          }
        }
      }

      if (start >= end || start < 0) {
        return false;
      }
      if (len < 0) {
        for (i = end - 1, el = (end += len); i >= el; i--) {
          if (/[\uDC00-\uDFFF]/.test(str.charAt(i)) && /[\uD800-\uDBFF]/.test(str.charAt(i - 1))) {
            end--;
            el--;
          }
        }
        if (start > end) {
          return false;
        }
        return str.slice(start, end);
      } else {
        se = start + len;
        for (i = start; i < se; i++) {
          ret += str.charAt(i);
          if (/[\uD800-\uDBFF]/.test(str.charAt(i)) && /[\uDC00-\uDFFF]/.test(str.charAt(i + 1))) {
            se++; // Go one further, since one of the "characters" is part of a surrogate pair
          }
        }
        return ret;
      }
      break;
    }
    // Fall-through
  case 'off':
    // assumes there are no non-BMP characters;
    //    if there may be such characters, then it is best to turn it on (critical in true XHTML/XML)
  default:
    if (start < 0) {
      start += end;
    }
    end = typeof len === 'undefined' ? end : (len < 0 ? len + end : len + start);
    // PHP returns false if start does not fall within the string.
    // PHP returns false if the calculated end comes before the calculated start.
    // PHP returns an empty string if start and end are the same.
    // Otherwise, PHP returns the portion of the string from start to end.
    return start >= str.length || start < 0 || start > end ? !1 : str.slice(start, end);
  }
  return undefined; // Please Netbeans
}

function strtotime (text, now) {
    // Convert string representation of date and time to a timestamp
    //
    // version: 1109.2015
    // discuss at: http://phpjs.org/functions/strtotime
    // +   original by: Caio Ariede (http://caioariede.com)
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +      input by: David
    // +   improved by: Caio Ariede (http://caioariede.com)
    // +   bugfixed by: Wagner B. Soares
    // +   bugfixed by: Artur Tchernychev
    // +   improved by: A. Matías Quezada (http://amatiasq.com)
    // +   improved by: preuter
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // %        note 1: Examples all have a fixed timestamp to prevent tests to fail because of variable time(zones)
    // *     example 1: strtotime('+1 day', 1129633200);
    // *     returns 1: 1129719600
    // *     example 2: strtotime('+1 week 2 days 4 hours 2 seconds', 1129633200);
    // *     returns 2: 1130425202
    // *     example 3: strtotime('last month', 1129633200);
    // *     returns 3: 1127041200
    // *     example 4: strtotime('2009-05-04 08:30:00');
    // *     returns 4: 1241418600
    var parsed, match, year, date, days, ranges, len, times, regex, i;

    if (!text) {
        return null;
    }

    // Unecessary spaces
    text = text.trim()
        .replace(/\s{2,}/g, ' ')
        .replace(/[\t\r\n]/g, '')
        .toLowerCase();

    if (text === 'now') {
        return now === null || isNaN(now) ? new Date().getTime() / 1000 | 0 : now | 0;
    }
    if (!isNaN(parsed = Date.parse(text))) {
        return parsed / 1000 | 0;
    }
    if (text === 'now') {
        return new Date().getTime() / 1000; // Return seconds, not milli-seconds
    }
    if (!isNaN(parsed = Date.parse(text))) {
        return parsed / 1000;
    }

    match = text.match(/^(\d{2,4})-(\d{2})-(\d{2})(?:\s(\d{1,2}):(\d{2})(?::\d{2})?)?(?:\.(\d+)?)?$/);
    if (match) {
        year = match[1] >= 0 && match[1] <= 69 ? +match[1] + 2000 : match[1];
        return new Date(year, parseInt(match[2], 10) - 1, match[3],
            match[4] || 0, match[5] || 0, match[6] || 0, match[7] || 0) / 1000;
    }

    date = now ? new Date(now * 1000) : new Date();
    days = {
        'sun': 0,
        'mon': 1,
        'tue': 2,
        'wed': 3,
        'thu': 4,
        'fri': 5,
        'sat': 6
    };
    ranges = {
        'yea': 'FullYear',
        'mon': 'Month',
        'day': 'Date',
        'hou': 'Hours',
        'min': 'Minutes',
        'sec': 'Seconds'
    };

    function lastNext(type, range, modifier) {
        var diff, day = days[range];

        if (typeof day !== 'undefined') {
            diff = day - date.getDay();

            if (diff === 0) {
                diff = 7 * modifier;
            }
            else if (diff > 0 && type === 'last') {
                diff -= 7;
            }
            else if (diff < 0 && type === 'next') {
                diff += 7;
            }

            date.setDate(date.getDate() + diff);
        }
    }
    function process(val) {
        var splt = val.split(' '), // Todo: Reconcile this with regex using \s, taking into account browser issues with split and regexes
            type = splt[0],
            range = splt[1].substring(0, 3),
            typeIsNumber = /\d+/.test(type),
            ago = splt[2] === 'ago',
            num = (type === 'last' ? -1 : 1) * (ago ? -1 : 1);

        if (typeIsNumber) {
            num *= parseInt(type, 10);
        }

        if (ranges.hasOwnProperty(range) && !splt[1].match(/^mon(day|\.)?$/i)) {
            return date['set' + ranges[range]](date['get' + ranges[range]]() + num);
        }
        if (range === 'wee') {
            return date.setDate(date.getDate() + (num * 7));
        }

        if (type === 'next' || type === 'last') {
            lastNext(type, range, num);
        }
        else if (!typeIsNumber) {
            return false;
        }
        return true;
    }

    times = '(years?|months?|weeks?|days?|hours?|minutes?|min|seconds?|sec' +
        '|sunday|sun\\.?|monday|mon\\.?|tuesday|tue\\.?|wednesday|wed\\.?' +
        '|thursday|thu\\.?|friday|fri\\.?|saturday|sat\\.?)';
    regex = '([+-]?\\d+\\s' + times + '|' + '(last|next)\\s' + times + ')(\\sago)?';

    match = text.match(new RegExp(regex, 'gi'));
    if (!match) {
        return false;
    }

    for (i = 0, len = match.length; i < len; i++) {
        if (!process(match[i])) {
            return false;
        }
    }

    // ECMAScript 5 only
    //if (!match.every(process))
    //    return false;

    return (date.getTime() / 1000);
}

//필드 byte수 cntChar(입력필드ID, 바이트수표시영역 ID, 최대byte, 넘어가면 삭제할건지 )
function cntChar(field_id, cntViewArea, maxCnt, is_del) {
 var overCharCnt = 3;
 var strCount = 0;
 var tempStr, tempStr2;
 var val = $('#'+field_id).val();

 for(i = 0;i < val.length;i++) {
  tempStr = val.charAt(i);
  if(escape(tempStr).length > 4) {
   strCount += overCharCnt;
       } else {
   strCount += 1 ;
  }
    }
 if(strCount > maxCnt && is_del == '1') {
  //alert("최대 " + maxCnt + "byte이므로 초과된 글자수는 자동으로 삭제됩니다.");  
  strCount = 0;  
  tempStr2 = "";
  
  for(i = 0; i < val.length; i++) {
   tempStr = val.charAt(i); 
   
   if(escape(tempStr).length > 4) {
    strCount += overCharCnt;
   } else {
    strCount += 1 ;
   }
        if(strCount > maxCnt) {
    if(escape(tempStr).length > 4) {
     strCount -= overCharCnt;
         } else {
     strCount -= 1 ; 
    }
         break;         
        } else {
    tempStr2 += tempStr;
   }
  }     
  $('#'+field_id).val(tempStr2);
 }
 $('#'+cntViewArea).html(strCount);
}


//달력
$(document).ready(function(){
	$('.datepicker_noBtn1').each(function(){

		//버튼이미지
		if($( this ).hasClass('birth')){
			var defaultDate = "-25y";
			var minDate = "-100y";
			var maxDate = "+0y";
			var yearRange = "c-100:c";
		}else{
			var defaultDate = "";
			var minDate = "-30y";
			var maxDate = "";
			var yearRange = "c-100:c+10";
		}

		var clareCalendar = {
			inline: true, 
			dateFormat: "yy-mm-dd",    /* 날짜 포맷 */ 
			prevText: 'prev', 
			nextText: 'next', 
			showButtonPanel: true,    /* 버튼 패널 사용 */ 
			changeMonth: true,        /* 월 선택박스 사용 */ 
			changeYear: true,        /* 년 선택박스 사용 */ 
			showOtherMonths: true,    /* 이전/다음 달 일수 보이기 */ 
			selectOtherMonths: true,    /* 이전/다음 달 일 선택하기 */ 
			showOn: "focus", 
			buttonImageOnly: false, 
			minDate: minDate, 
			maxDate: maxDate, 
			closeText: '닫기', 
			currentText: '오늘', 
			showMonthAfterYear: true,        /* 년과 달의 위치 바꾸기 */ 
			
			defaultDate: defaultDate,
			yearRange : yearRange,
			/* 한글화 */ 
			monthNames : ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'], 
			monthNamesShort : ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'], 
			dayNames : ['일', '월', '화', '수', '목', '금', '토'],
			dayNamesShort : ['일', '월', '화', '수', '목', '금', '토'],
			dayNamesMin : ['일', '월', '화', '수', '목', '금', '토'],

			showAnim: 'slideDown', 
			showOptions: { direction: "down" },

			/* 날짜 유효성 체크 */
			/*
			onClose: function( selectedDate ) {
				if(selectedDate){
					var ids = $(this).attr('id') + '_disp';
					var d = new Date(selectedDate);
					$('#'+ids).html(selectedDate + '<br/>(' + clareCalendar.dayNames[d.getDay()] + '요일)');
				}
			}
			*/
		}

		$( this ).datepicker(clareCalendar); 
	});
});

//달력
$(document).ready(function(){
	$('.datepicker').each(function(){
		//버튼이미지
		if($( this ).hasClass('calstyle1')){
			var canBtnStyle = "/image/icon_btn_calendar.gif";
			var useBtnStyle = true;
		}else if($( this ).hasClass('calstyle2')){
			var canBtnStyle = "/image/icon_btn_calendar2.gif";
			var useBtnStyle = true;
		}else{
			var canBtnStyle = "/image/icon_btn_calendar.gif";
			var useBtnStyle = true;
		}

		//버튼이미지
		if($( this ).hasClass('birth')){
			var defaultDate = "-25y";
			var minDate = "-100y";
			var maxDate = "+0y";
			var yearRange = "c-100:c";
		}else{
			var defaultDate = "";
			var minDate = "-30y";
			var maxDate = "";
			var yearRange = "c-100:c+10";
		}


		var clareCalendar = {
			inline: true, 
			dateFormat: "yy-mm-dd",    /* 날짜 포맷 */ 
			prevText: 'prev', 
			nextText: 'next', 
			showButtonPanel: true,    /* 버튼 패널 사용 */ 
			changeMonth: true,        /* 월 선택박스 사용 */ 
			changeYear: true,        /* 년 선택박스 사용 */ 
			showOtherMonths: true,    /* 이전/다음 달 일수 보이기 */ 
			selectOtherMonths: true,    /* 이전/다음 달 일 선택하기 */ 
			showOn: "button", 
			buttonImage: canBtnStyle, 
			buttonImageOnly: true, 
			minDate: minDate, 
			maxDate: maxDate, 
			closeText: '닫기', 
			currentText: '오늘', 
			showMonthAfterYear: true,        /* 년과 달의 위치 바꾸기 */ 
			
			defaultDate: defaultDate,
			yearRange : yearRange,
			/* 한글화 */ 
			monthNames : ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'], 
			monthNamesShort : ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'], 
			dayNames : ['일', '월', '화', '수', '목', '금', '토'],
			dayNamesShort : ['일', '월', '화', '수', '목', '금', '토'],
			dayNamesMin : ['일', '월', '화', '수', '목', '금', '토'],

			showAnim: 'slideDown', 
			showOptions: { direction: "down" },
			/* 날짜 유효성 체크 */
			/*
			onClose: function( selectedDate ) { 
				$('#fromDate').datepicker("option","minDate", selectedDate); 
			}
			*/
		}

		$( this ).datepicker(clareCalendar); 
	});
});

function add_cal_all(){
	$('.datepicker').each(function(){
		//버튼이미지
		if($( this ).hasClass('calstyle1')){
			var canBtnStyle = "/image/icon_btn_calendar.gif";
			var useBtnStyle = true;
		}else if($( this ).hasClass('calstyle2')){
			var canBtnStyle = "/image/icon_btn_calendar2.gif";
			var useBtnStyle = true;
		}else if($( this ).hasClass('calstyle_no')){
			var canBtnStyle = null;
			var useBtnStyle = false;
		}else{
			var canBtnStyle = "/image/icon_btn_calendar.gif";
			var useBtnStyle = true;
		}


		//버튼이미지
		if($( this ).hasClass('birth')){
			var defaultDate = "-25y";
			var minDate = "-100y";
			var maxDate = "+0y";
			var yearRange = "c-100:c";
		}else{
			var defaultDate = "";
			var minDate = "-30y";
			var maxDate = "";
			var yearRange = "c-100:c+10";
		}
		var clareCalendar = {
			inline: true, 
			dateFormat: "yy-mm-dd",    /* 날짜 포맷 */ 
			prevText: 'prev', 
			nextText: 'next', 
			showButtonPanel: true,    /* 버튼 패널 사용 */ 
			changeMonth: true,        /* 월 선택박스 사용 */ 
			changeYear: true,        /* 년 선택박스 사용 */ 
			showOtherMonths: true,    /* 이전/다음 달 일수 보이기 */ 
			selectOtherMonths: true,    /* 이전/다음 달 일 선택하기 */ 
			showOn: "button", 
			buttonImage: canBtnStyle, 
			buttonImageOnly: true, 
			minDate: minDate, 
			maxDate: maxDate, 
			closeText: '닫기', 
			currentText: '오늘', 
			showMonthAfterYear: true,        /* 년과 달의 위치 바꾸기 */ 
			
			defaultDate: defaultDate,
			yearRange : yearRange,
			/* 한글화 */ 
			monthNames : ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'], 
			monthNamesShort : ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'], 
			dayNames : ['일', '월', '화', '수', '목', '금', '토'],
			dayNamesShort : ['일', '월', '화', '수', '목', '금', '토'],
			dayNamesMin : ['일', '월', '화', '수', '목', '금', '토'],

			showAnim: 'slideDown', 
			showOptions: { direction: "down" },
			/* 날짜 유효성 체크 */
			/*
			onClose: function( selectedDate ) { 
				$('#fromDate').datepicker("option","minDate", selectedDate); 
			}
			*/
		}

		$( this ).datepicker(clareCalendar); 
	});
}

function add_cal(ids)
{
		//버튼이미지
		if($( '#'+ ids).hasClass('calstyle1')){
			var canBtnStyle = "/image/icon_btn_calendar.gif";
			var useBtnStyle = true;
		}else if($( '#'+ ids ).hasClass('calstyle2')){
			var canBtnStyle = "/image/icon_btn_calendar2.gif";
			var useBtnStyle = true;
		}else if($( '#'+ ids ).hasClass('calstyle_no')){
			var canBtnStyle = null;
			var useBtnStyle = false;
		}else{
			var canBtnStyle = "/image/icon_btn_calendar.gif";
			var useBtnStyle = true;
		}


		//버튼이미지
		if($( this ).hasClass('birth')){
			var defaultDate = "-25y";
			var minDate = "-100y";
			var maxDate = "+0y";
			var yearRange = "c-100:c";
		}else{
			var defaultDate = "";
			var minDate = "-30y";
			var maxDate = "";
			var yearRange = "c-100:c+10";
		}
		var clareCalendar = {
			inline: true, 
			dateFormat: "yy-mm-dd",    /* 날짜 포맷 */ 
			prevText: 'prev', 
			nextText: 'next', 
			showButtonPanel: true,    /* 버튼 패널 사용 */ 
			changeMonth: true,        /* 월 선택박스 사용 */ 
			changeYear: true,        /* 년 선택박스 사용 */ 
			showOtherMonths: true,    /* 이전/다음 달 일수 보이기 */ 
			selectOtherMonths: true,    /* 이전/다음 달 일 선택하기 */ 
			showOn: "button", 
			buttonImage: canBtnStyle, 
			buttonImageOnly: true, 
			minDate: minDate, 
			maxDate: maxDate, 
			closeText: '닫기', 
			currentText: '오늘', 
			showMonthAfterYear: true,        /* 년과 달의 위치 바꾸기 */ 
			
			defaultDate: defaultDate,
			yearRange : yearRange,
			/* 한글화 */ 
			monthNames : ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'], 
			monthNamesShort : ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'], 
			dayNames : ['일', '월', '화', '수', '목', '금', '토'],
			dayNamesShort : ['일', '월', '화', '수', '목', '금', '토'],
			dayNamesMin : ['일', '월', '화', '수', '목', '금', '토'],

			showAnim: 'slideDown', 
			showOptions: { direction: "down" },
			/* 날짜 유효성 체크 */
			/*
			onClose: function( selectedDate ) { 
				$('#fromDate').datepicker("option","minDate", selectedDate); 
			}
			*/
		}

		$( '#'+ ids ).datepicker(clareCalendar); 
}


function chn2(obj){
	document.getElementById("tab_view"+1).style.display = "none";
	document.getElementById("tab_view"+2).style.display = "none";
	document.getElementById("tab_view"+obj).style.display = "block";
}

function ViewObj(obj,arg){
	var el = document.getElementById(obj);
	if(arg=='1'){		
		el.style.display = "block";
	}else if(arg=='0'){		
		el.style.display = "none";
	}
}

function user_goto_login()
{
	alert('로그인이 필요한 서비스 입니다.');
	if(org_user_agent == "mobile" && user_agent == "app"){
		if (!!(window.history && history.replaceState)) {
			window.history.replaceState({}, document.title, '/member/login.php');
		}
	}
	location.replace('/member/login.php')
}

function calDistance(lat1, lon1, lat2, lon2) {
	var R = 6371; // km
	var startLatRad = degreesToRadians(lat1);
	var startLonRad = degreesToRadians(lon1);

	var destLatRad = degreesToRadians(lat2);
	var destLonRad = degreesToRadians(lon2);

	var distance = Math.acos(Math.sin(startLatRad) * Math.sin(destLatRad) + Math.cos(startLatRad) * Math.cos(destLatRad) * Math.cos(startLonRad - destLonRad)) * R;

	if(distance >= 1)
	{
		loc_str =  Math.round(distance * 10) / 10; //소수점 한자리에서 무조건 반올림
		return loc_str + 'km';
	}
	else
	{
		loc_str =  Math.round(distance * 1000 , 2); //소수점 세자리에서 무조건 반올림
		return loc_str + 'm';
	}
}

function degreesToRadians(degrees){
	var radians = (degrees * Math.PI) / 180;
	return radians;
}


function js_trim(val) 
{
	var pattern = /(^\s*)|(\s*$)/g; // \s 공백 문자
	val = val.replace(pattern, "");
	return val;
}

function numberWithCommas(x) {
	return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}