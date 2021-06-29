if (typeof(WREST_JS) == 'undefined') // 한번만 실행
{
	function getBrowserType(mode){
		  
		var _ua = navigator.userAgent;
		var rv = -1;
		 
		//IE 11,10,9,8
		var trident = _ua.match(/Trident\/(\d.\d)/i);
		if( trident != null )
		{
			if(mode == 1){
				if( trident[1] == "7.0" ) return rv = "IE" + 11;
				if( trident[1] == "6.0" ) return rv = "IE" + 10;
				if( trident[1] == "5.0" ) return rv = "IE" + 9;
				if( trident[1] == "4.0" ) return rv = "IE" + 8;
			}else{
				if( trident[1] == "7.0" ) return rv = "IE";
				if( trident[1] == "6.0" ) return rv = "IE";
				if( trident[1] == "5.0" ) return rv = "IE";
				if( trident[1] == "4.0" ) return rv = "IE";
			}
		}
		 
		//IE 7...
		if( navigator.appName == 'Microsoft Internet Explorer' ){
			if(mode == 1){
				return rv = "IE" + 7;
			}else{
				return rv = "IE";
			}
		}

		if(mode == 1){
		}

		/*
		var re = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
		if(re.exec(_ua) != null) rv = parseFloat(RegExp.$1);
		if( rv == 7 ) return rv = "IE" + 7; 
		*/
		 
		//other
		var agt = _ua.toLowerCase();
		if(mode == 1){
			if (agt.indexOf("chrome") != -1) return 'Chrome';
			if (agt.indexOf("opera") != -1) return 'Opera'; 
			if (agt.indexOf("staroffice") != -1) return 'Star Office'; 
			if (agt.indexOf("webtv") != -1) return 'WebTV'; 
			if (agt.indexOf("beonex") != -1) return 'Beonex'; 
			if (agt.indexOf("chimera") != -1) return 'Chimera'; 
			if (agt.indexOf("netpositive") != -1) return 'NetPositive'; 
			if (agt.indexOf("phoenix") != -1) return 'Phoenix'; 
			if (agt.indexOf("firefox") != -1) return 'Firefox'; 
			if (agt.indexOf("safari") != -1) return 'Safari'; 
			if (agt.indexOf("skipstone") != -1) return 'SkipStone'; 
			if (agt.indexOf("netscape") != -1) return 'Netscape'; 
			if (agt.indexOf("mozilla/5.0") != -1) return 'Mozilla';
		}else{
			return "";
		}
	}

	function wrestCheckAttrExist(fld, attr)
	{
		var tmp_val = fld.getAttribute(attr);
		if(W_browser == "IE" && fld.type == "select-one" && attr == "required"){
			if(tmp_val == "required"){
				return "";
			}else{
				return null;
			}
		}else{
			return tmp_val;
		}
	}

	var W_browser = getBrowserType();


    var WREST_JS = true;

    var wrestMsg = '';
    var wrestFld = null;
    //var wrestFldDefaultColor = '#FFFFFF'; 
    var wrestFldDefaultColor = ''; 
    var wrestFldBackColor = '#FFE4E1'; 
    var arrAttr  = new Array ('required', 'afterinput', 'afterradio', 'afterselect', 'aftercheckbox', 'trim', 'minlength', 'email', 'email1', 'email2', 'hangul', 'hangul2', 
                              'memberid', 'nospace', 'numeric', 'numericfloat', 'minusnumeric', 'minusnumericfloat', 'overzero', 'undernum', 'alpha', 'alphanumeric', 'lowalphanumeric', 
                              'jumin', 'saupja', 'alphanumericunderline', 'telnumber', 'hangulalphanumeric', 'hangulalphacap', 'hangulnumeric', 'passwd');

    // subject 속성값을 얻어 return, 없으면 tag의 name을 넘김
    function wrestItemname(fld)
    {
        var itemname = fld.getAttribute("itemname");
        if (itemname != null && itemname != "")
            return itemname;
        else
            return fld.name;
    }


	// 참조하는 인풋 밸류를 얻어 조건값이 참이면 return
	function wrestAfterInputVal(fld)
    {
        var input_name = fld.getAttribute("afterinput");
		if(input_name != null && input_name != "")var input_val = $('input[name='+input_name+']').val();;

		var correct_val_tmp = fld.getAttribute("afterinputval");
		var correct_val = correct_val_tmp.split("|");


		if(correct_val.length == 1){
			if (input_val == correct_val)
				return true;
			else
				return false;
		}else{
			var return_val = false;
			for(var i=0;i<correct_val.length;i++){
				if (input_val == correct_val[i])return_val =  true;
			}
			return return_val;
		}
    }

	// 참조하는 라디오 밸류를 얻어 조건값이 참이면 return
	function wrestAfterRadioVal(fld)
    {
        var radio_name = fld.getAttribute("afterradio");
		if(radio_name != null && radio_name != "")var radio_val = find_radio_value(radio_name);

		var correct_val_tmp = fld.getAttribute("afterradioval");
		var correct_val = correct_val_tmp.split("|");

		if(correct_val.length == 1){
			if (radio_val == correct_val)
				return true;
			else
				return false;
		}else{
			var return_val = false;
			for(var i=0;i<correct_val.length;i++){
				if (radio_val == correct_val[i])return_val =  true;
			}
			return return_val;
		}
    }

	// 참조하는 셀렉트 밸류를 얻어 조건값이 참이면 return
	function wrestAfterSelectVal(fld, f)
    {
        var select_name = fld.getAttribute("afterselect");
		if(select_name != null && select_name != ""){
			var select_fld = eval("f."+select_name);
			var select_val = wrestTrim(select_fld);
		}

		var correct_val_tmp = fld.getAttribute("afterselectval");
		var correct_val = correct_val_tmp.split("|");

		if(correct_val.length == 1){
			if (select_val == correct_val)
				return true;
			else
				return false;
		}else{
			var return_val = false;
			for(var i=0;i<correct_val.length;i++){
				if (select_val == correct_val[i])return_val =  true;
			}
			return return_val;
		}
    }

	// 참조하는 체크박스 체크여부를 얻어 조건값이 참이면 return
	function wrestAfterCheckboxVal(fld, f)
    {
        var checkbox_name = fld.getAttribute("aftercheckbox");
		if(checkbox_name != null && checkbox_name != ""){
			var checkbox_fld = eval("f."+checkbox_name);
			var checkbox_checked = checkbox_fld.checked;
		}

		var correct_val = fld.getAttribute("aftercheckboxval");

		if ((correct_val == 1 && checkbox_checked == true) || (correct_val == 0 && checkbox_checked == false))
			return true;
		else
			return false;
    }

    // 양쪽 공백 없애기
    function wrestTrim(fld) 
    {
        var pattern = /(^\s*)|(\s*$)/g; // \s 공백 문자
        fld.value = fld.value.replace(pattern, "");
        return fld.value;
    }

    // 필수 입력 검사
    function wrestRequired(fld)
    {
		if(fld.type == "radio"){
			var radio_name = fld.name;
			var radio_val = "";
			if(radio_name != null && radio_name != "")radio_val = find_radio_value(radio_name);
			if(radio_val == "")
			{
				if (wrestFld == null) 
				{
					wrestMsg = wrestItemname(fld) + " : 필수 선택입니다.\n";
					wrestFld = fld;
				}
			}
		}else{
			if (wrestTrim(fld) == "") 
			{
				if (wrestFld == null) 
				{
					// 3.30
					// 셀렉트박스일 경우에도 required 선택 검사합니다.
					wrestMsg = wrestItemname(fld) + " : 필수 "+(fld.type=="select-one"?"선택":"입력")+"입니다.\n";
					wrestFld = fld;
				}
			}
		}
    }


	// 인풋값에 따라 required항목 검사
	function wrestAfterInput(fld)
    {
		if(wrestAfterInputVal(fld)){
			if (wrestTrim(fld) == "") 
			{
				if (wrestFld == null)
				{alert(1);
					wrestMsg = wrestItemname(fld) + " : 필수 "+(fld.type=="select-one"?"선택":"입력")+"입니다.\n";
					wrestFld = fld;
				}
			}
		}
    }

	// 라이오 선택에 따라 required항목 검사
	function wrestAfterRadio(fld)
    {
		if(wrestAfterRadioVal(fld)){
			if (wrestTrim(fld) == "") 
			{
				if (wrestFld == null)
				{
					wrestMsg = wrestItemname(fld) + " : 필수 "+(fld.type=="select-one"?"선택":"입력")+"입니다.\n";
					wrestFld = fld;
				}
			}
		}
    }

	// 셀렉트 선택에 따라 required항목 검사
	function wrestAfterSelect(fld, f)
    {
		if(wrestAfterSelectVal(fld, f)){
			if (wrestTrim(fld) == "") 
			{
				if (wrestFld == null) 
				{
					wrestMsg = wrestItemname(fld) + " : 필수 "+(fld.type=="select-one"?"선택":"입력")+"입니다.\n";
					wrestFld = fld;
				}
			}
		}
    }
	// 체크박스 체크여부에 따라 required항목 검사
	function wrestAfterCheckbox(fld, f)
    {
		//return;
		if(wrestAfterCheckboxVal(fld, f)){
			if (wrestTrim(fld) == "") 
			{
				if (wrestFld == null) 
				{
					wrestMsg = wrestItemname(fld) + " : 필수 "+(fld.type=="select-one"?"선택":"입력")+"입니다.\n";
					wrestFld = fld;
				}
			}
		}
    }

    // 최소 길이 검사
    function wrestMinlength(fld)
    {
        var len = fld.getAttribute("minlength");
        if (fld.value.length < len) 
        {
            if (wrestFld == null) 
            {
                wrestMsg = wrestItemname(fld) + " :  최소 " + len + "자 이상 입력하세요.\n";
                wrestFld = fld;
            }
        }
    }

    // 김선용 2006.3 - 전화번호(휴대폰) 형식 검사 : 123-123(4)-5678
	function wrestTelnumber(fld){

		if (!wrestTrim(fld)) return;

		var pattern = /^[0-9]{2,3}-[0-9]{3,4}-[0-9]{4}$/;
		if(!pattern.test(fld.value)){ 
            if(wrestFld == null){
				wrestMsg = wrestItemname(fld)+" : 전화번호 형식이 올바르지 않습니다.\n\n하이픈(-)을 포함하여 입력해 주십시오.\n";
                wrestFld = fld;
				fld.select();
            }
		}
	}

    // 도메인 형식 검사
    function wrestDomain(fld) 
    {
        if (!wrestTrim(fld)) return;

        //var pattern = /(\S+)@(\S+)\.(\S+)/; 이메일주소에 한글 사용시
        var pattern = /^([a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,6}$/;
        if (!pattern.test(fld.value)) 
        {
            if (wrestFld == null) 
            {
                wrestMsg = wrestItemname(fld) + " : 도메인 형식이 아닙니다.\n";
                wrestFld = fld;
            }
        }
    }

    // 이메일주소 형식 검사
    function wrestEmail(fld) 
    {
        if (!wrestTrim(fld)) return;

        //var pattern = /(\S+)@(\S+)\.(\S+)/; 이메일주소에 한글 사용시
        var pattern = /([0-9a-zA-Z_-]+)@([0-9a-zA-Z_-]+)\.([0-9a-zA-Z_-]+)/;
        if (!pattern.test(fld.value)) 
        {
            if (wrestFld == null) 
            {
                wrestMsg = wrestItemname(fld) + " : 이메일주소 형식이 아닙니다.\n";
                wrestFld = fld;
            }
        }
    }

    // 이메일주소 형식 검사 (앞부분)
    function wrestEmail1(fld) 
    {
        if (!wrestTrim(fld)) return;

        //var pattern = /(\S+)@(\S+)\.(\S+)/; 이메일주소에 한글 사용시
        var pattern = /([0-9a-zA-Z_-]+)/;
        if (!pattern.test(fld.value)) 
        {
            if (wrestFld == null) 
            {
                wrestMsg = wrestItemname(fld) + " : 이메일주소 형식이 아닙니다.\n";
                wrestFld = fld;
            }
        }
    }

    // 이메일주소 형식 검사 (뒷부분)
    function wrestEmail2(fld) 
    {
        if (!wrestTrim(fld)) return;

        //var pattern = /(\S+)@(\S+)\.(\S+)/; 이메일주소에 한글 사용시
        var pattern = /([0-9a-zA-Z_-]+)\.([0-9a-zA-Z_-]+)/;
        if (!pattern.test(fld.value)) 
        {
            if (wrestFld == null) 
            {
                wrestMsg = wrestItemname(fld) + " : 이메일주소 형식이 아닙니다.\n";
                wrestFld = fld;
            }
        }
    }

    // 회원아이디 검사
    function wrestMemberId(fld) 
    {
        if (!wrestTrim(fld)) return;

        var pattern = /(^([a-z]+)([a-z0-9]+$))/;
        if (!pattern.test(fld.value)) 
        {
            if (wrestFld == null) 
            {
                wrestMsg = wrestItemname(fld) + " : 회원아이디 형식이 아닙니다.\n\n영소문자, 숫자만 가능.\n\n첫글자는 영소문자만 가능\n";
                wrestFld = fld;
            }
        }
    }

	// 패스워드 검사
	function wrestPasswd(fld)
    {
        if (!wrestTrim(fld)) return;

        var pattern = /^.*(?=.{8,})(?=.*[0-9])(?=.*[a-zA-Z])(?=.*[\{\}\[\]\/?.,;:|\)*~`!^\-_+┼<>@\#$%&\'\"\\\(\=]).*$/;
        if (!pattern.test(fld.value)) 
        {
            if (wrestFld == null) 
            {
                wrestMsg = wrestItemname(fld) + " : 영문 대.소문자, 숫자, 특수문자를 조합해서 사용하세요.\n";
                wrestFld = fld;
            }
        }
    }

    // 한글인지 검사 (자음, 모음만 있는 한글은 불가)
    function wrestHangul(fld) 
    { 
        if (!wrestTrim(fld)) return;

        var pattern = /([^가-힣\x20])/i; 

        if (pattern.test(fld.value)) 
        {
            if (wrestFld == null) 
            { 
                wrestMsg = wrestItemname(fld) + ' : 한글이 아닙니다. (자음, 모음만 있는 한글은 처리하지 않습니다.)\n'; 
                wrestFld = fld; 
            } 
        } 
    }

    // 한글인지 검사2 (자음, 모음만 있는 한글도 가능)
    function wrestHangul2(fld) 
    { 
        if (!wrestTrim(fld)) return;

        var pattern = /([^가-힣ㄱ-ㅎㅏ-ㅣ\x20])/i; 

        if (pattern.test(fld.value)) 
        {
            if (wrestFld == null) 
            { 
                wrestMsg = wrestItemname(fld) + ' : 한글이 아닙니다.\n'; 
                wrestFld = fld; 
            } 
        } 
    }

    // 한글,영문,숫자인지 검사3
    function wrestHangulAlphaNumeric(fld) 
    { 
        if (!wrestTrim(fld)) return;

        var pattern = /([^가-힣\x20^a-z^A-Z^0-9])/i; 

        if (pattern.test(fld.value)) 
        {
            if (wrestFld == null) 
            { 
                wrestMsg = wrestItemname(fld) + ' : 한글, 영문, 숫자가 아닙니다.\n'; 
                wrestFld = fld; 
            } 
        } 
    }

    // 한글,영문대문자인지 검사
    function wrestHangulAlphaCap(fld) 
    { 
        if (!wrestTrim(fld)) return;

        var pattern = /([^가-힣\x20^A-Z])/i; 

        if (pattern.test(fld.value)) 
        {
            if (wrestFld == null) 
            { 
                wrestMsg = wrestItemname(fld) + ' : 한글, 영문대문자가 아닙니다.\n'; 
                wrestFld = fld; 
            } 
        } 
    }


    // 한글,숫자인지 검사
    function wrestHangulNumeric(fld) 
    { 
        if (!wrestTrim(fld)) return;

        var pattern = /([^가-힣\x20^0-9])/i; 

        if (pattern.test(fld.value)) 
        {
            if (wrestFld == null) 
            { 
                wrestMsg = wrestItemname(fld) + ' : 한글, 숫자가 아닙니다.\n'; 
                wrestFld = fld; 
            } 
        } 
    }

    // 숫자인지검사 
    // 배부른꿀꿀이님 추가 (http://dasir.com) 2003-06-24
    function wrestNumeric(fld) 
    { 
        if (fld.value.length > 0) 
        { 
            for (i = 0; i < fld.value.length; i++) 
            { 
                if (fld.value.charAt(i) < '0' || fld.value.charAt(i) > '9') 
                { 
                    wrestMsg = wrestItemname(fld) + " : 숫자가 아닙니다.\n"; 
                    wrestFld = fld; 
                }
            }
        }
    }

	//wrestNumericFloat
    function wrestNumericFloat(fld) 
    { 
        if (fld.value.length > 0) 
        {
			var dot_cnt = 0;
			var last_num = fld.value.length - 1;

            for (i = 0; i < fld.value.length; i++) 
            {
				if(fld.value.charAt(i) == '.'){
					dot_cnt++;
				}

                if (
					(
						(fld.value.charAt(i) < '0' || fld.value.charAt(i) > '9') && fld.value.charAt(i) != '.' && (i > 0)
					)
					|| 
					(
						(fld.value.charAt(i) < '0' || fld.value.charAt(i) > '9') && (i == 0)
					)
					|| 
					(
						(fld.value.charAt(i) < '0' || fld.value.charAt(i) > '9') && fld.value.length < 3 && dot_cnt == 1
					)
					||
					fld.value.charAt(last_num) == '.'
					||
					dot_cnt > 1
				) 
                { 
                    wrestMsg = wrestItemname(fld) + " : 숫자가 아닙니다.\n"; 
                    wrestFld = fld; 
                }
            }
        }
    }


	//wrestMinusNumeric
    function wrestMinusNumeric(fld) 
    { 
        if (fld.value.length > 0) 
        { 
            for (i = 0; i < fld.value.length; i++) 
            { 
                if (
					(
						(fld.value.charAt(i) < '0' || fld.value.charAt(i) > '9') && (i > 0)
					)
					|| 
					(
						(fld.value.charAt(i) < '0' || fld.value.charAt(i) > '9') && fld.value.charAt(i) != '-' && (i == 0)
					)
					||
					(
						(fld.value.charAt(i) < '0' || fld.value.charAt(i) > '9') && fld.value.length == 1
					)
				) 
                { 
                    wrestMsg = wrestItemname(fld) + " : 숫자가 아닙니다.\n"; 
                    wrestFld = fld; 
                }
            }
        }
    }

	//wrestMinusNumericFloat
    function wrestMinusNumericFloat(fld) 
    { 
        if (fld.value.length > 0) 
        {
			var dot_cnt = 0;
			var is_minus = 0;
			var last_num = fld.value.length - 1;

			if(fld.value.charAt(0) == '-'){
				is_minus = 1;
			}else{
				is_minus = 0;
			}

            for (i = 0; i < fld.value.length; i++) 
            {
				if(fld.value.charAt(i) == '.'){
					dot_cnt++;
				}



				if(is_minus == 1){

					if (
						(
							(fld.value.charAt(i) < '0' || fld.value.charAt(i) > '9') && fld.value.charAt(i) != '.' && (i > 1)
						)
						|| 
						(
							(fld.value.charAt(i) < '0' || fld.value.charAt(i) > '9') && (i == 1)
						)
						|| 
						(
							(fld.value.charAt(i) < '0' || fld.value.charAt(i) > '9') && fld.value.length < 4 && dot_cnt == 1
						)
						||
						fld.value.charAt(last_num) == '.'
						||
						dot_cnt > 1
					) 
					{ 
						wrestMsg = wrestItemname(fld) + " : 숫자가 아닙니다.\n"; 
						wrestFld = fld; 
					}

				}else{
					if (
						(
							(fld.value.charAt(i) < '0' || fld.value.charAt(i) > '9') && fld.value.charAt(i) != '.' && (i > 0)
						)
						|| 
						(
							(fld.value.charAt(i) < '0' || fld.value.charAt(i) > '9') && (i == 0)
						)
						|| 
						(
							(fld.value.charAt(i) < '0' || fld.value.charAt(i) > '9') && fld.value.length < 3 && dot_cnt == 1
						)
						||
						fld.value.charAt(last_num) == '.'
						||
						dot_cnt > 1
					) 
					{ 
						wrestMsg = wrestItemname(fld) + " : 숫자가 아닙니다.\n"; 
						wrestFld = fld; 
					}
				}

            }
        }
    }

	function wrestOverZero(fld) 
    { 
		var min_num = get_int(fld.getAttribute("overzero"));
        if (fld.value.length > 0) 
        { 
            for (i = 0; i < fld.value.length; i++) 
            { 
                if (fld.value.charAt(i) < '0' || fld.value.charAt(i) > '9') 
                { 
                    wrestMsg = wrestItemname(fld) + " : 숫자가 아닙니다.\n"; 
                    wrestFld = fld; 
                }
            }

			if(get_int(fld.value) < min_num){
				wrestMsg = wrestItemname(fld) + " : "+min_num+" 이상의 숫자를 입력하셔야합니다.\n"; 
				wrestFld = fld;
			}
        }
    }

	function wrestUnderNum(fld) 
    { 
		var max_num = get_int(fld.getAttribute("undernum"));
        if (fld.value.length > 0) 
        { 
            for (i = 0; i < fld.value.length; i++) 
            { 
                if (fld.value.charAt(i) < '0' || fld.value.charAt(i) > '9') 
                { 
                    wrestMsg = wrestItemname(fld) + " : 숫자가 아닙니다.\n"; 
                    wrestFld = fld; 
                }
            }

			if(get_int(fld.value) > max_num){
				wrestMsg = wrestItemname(fld) + " : "+max_num+" 이하의 숫자를 입력하셔야합니다.\n"; 
				wrestFld = fld;
			}
        }
    }

    // 영문자 검사 
    // 배부른꿀꿀이님 추가 (http://dasir.com) 2003-06-24
    function wrestAlpha(fld) 
    { 
        if (!wrestTrim(fld)) return; 

        var pattern = /(^[a-zA-Z]+$)/; 
        if (!pattern.test(fld.value)) 
        { 
            if (wrestFld == null) 
            { 
                wrestMsg = wrestItemname(fld) + " : 영문이 아닙니다.\n"; 
                wrestFld = fld; 
            } 
        } 
    } 

    // 영문자와 숫자 검사 
    // 배부른꿀꿀이님 추가 (http://dasir.com) 2003-07-07
    function wrestAlphaNumeric(fld) 
    { 
       if (!wrestTrim(fld)) return; 
       var pattern = /(^[a-zA-Z0-9]+$)/; 
       if (!pattern.test(fld.value)) 
       { 
           if (wrestFld == null) 
           { 
               wrestMsg = wrestItemname(fld) + " : 영문 또는 숫자가 아닙니다.\n"; 
               wrestFld = fld; 
           } 
       } 
    } 


    // 영소문자와 숫자 검사 
    function wrestLowAlphaNumeric(fld) 
    { 
       if (!wrestTrim(fld)) return; 
       var pattern = /(^[a-z0-9]+$)/; 
       if (!pattern.test(fld.value)) 
       { 
           if (wrestFld == null) 
           { 
               wrestMsg = wrestItemname(fld) + " : 영문소문자 또는 숫자가 아닙니다.\n"; 
               wrestFld = fld; 
           } 
       } 
    }

    // 영문자와 숫자 그리고 _ 검사 
    function wrestAlphaNumericUnderLine(fld) 
    { 
       if (!wrestTrim(fld)) 
           return; 

       var pattern = /(^[a-zA-Z0-9\_]+$)/; 
       if (!pattern.test(fld.value)) 
       { 
           if (wrestFld == null) 
           { 
               wrestMsg = wrestItemname(fld) + " : 영문, 숫자, _ 가 아닙니다.\n"; 
               wrestFld = fld; 
           } 
       } 
    } 

    // 주민등록번호 검사
    function wrestJumin(fld) 
    { 
       if (!wrestTrim(fld)) return; 
       var pattern = /(^[0-9]{13}$)/; 
       if (!pattern.test(fld.value)) 
       { 
           if (wrestFld == null) 
           { 
               wrestMsg = wrestItemname(fld) + " : 주민등록번호를 13자리 숫자로 입력하십시오.\n"; 
               wrestFld = fld; 
           } 
       } 
       else 
       {
            var sum_1 = 0;
            var sum_2 = 0;
            var at=0;
            var juminno= fld.value;
            sum_1 = (juminno.charAt(0)*2)+
                    (juminno.charAt(1)*3)+
                    (juminno.charAt(2)*4)+
                    (juminno.charAt(3)*5)+
                    (juminno.charAt(4)*6)+
                    (juminno.charAt(5)*7)+
                    (juminno.charAt(6)*8)+
                    (juminno.charAt(7)*9)+
                    (juminno.charAt(8)*2)+
                    (juminno.charAt(9)*3)+
                    (juminno.charAt(10)*4)+
                    (juminno.charAt(11)*5);
            sum_2=sum_1 % 11;

            if (sum_2 == 0) 
                at = 10;
            else 
            {
                if (sum_2 == 1) 
                    at = 11;
                else 
                    at = sum_2;
            }
            att = 11 - at;
            // 1800 년대에 태어나신 분들은 남자, 여자의 구분이 9, 0 이라는 
            // 얘기를 들은적이 있는데 그렇다면 아래의 구문은 오류이다.
            // 하지만... 100살넘은 분들이 주민등록번호를 과연 입력해볼까?
            if (juminno.charAt(12) != att || 
                juminno.substr(2,2) < '01' ||
                juminno.substr(2,2) > '12' ||
                juminno.substr(4,2) < '01' ||
                juminno.substr(4,2) > '31' ||
                juminno.charAt(6) > 4) 
            {
               wrestMsg = wrestItemname(fld) + " : 올바른 주민등록번호가 아닙니다.\n"; 
               wrestFld = fld; 
            }

        }
    } 

    // 사업자등록번호 검사
    function wrestSaupja(fld) 
    { 
       if (!wrestTrim(fld)) return; 
       var pattern = /^\d{3}-{0,1}\d{2}-{0,1}\d{5}$/; 
       if (!pattern.test(fld.value)) 
       { 
           if (wrestFld == null) 
           { 
               wrestMsg = wrestItemname(fld) + " : 사업자등록번호를 정확히 입력하십시오.\n"; 
               wrestFld = fld; 
           } 
       } 
       else 
       {
            var sum = 0;
            var at = 0;
            var att = 0;
            var saupjano= fld.value;
            sum = (saupjano.charAt(0)*1)+
                  (saupjano.charAt(1)*3)+
                  (saupjano.charAt(2)*7)+
                  (saupjano.charAt(4)*1)+
                  (saupjano.charAt(5)*3)+
                  (saupjano.charAt(7)*7)+
                  (saupjano.charAt(8)*1)+
                  (saupjano.charAt(9)*3)+
                  (saupjano.charAt(10)*5);
            sum += parseInt((saupjano.charAt(10)*5)/10);
            at = sum % 10;
            if (at != 0) 
                att = 10 - at;  

            if (saupjano.charAt(11) != att) 
            {
               wrestMsg = wrestItemname(fld) + " : 올바른 사업자등록번호가 아닙니다.\n"; 
               wrestFld = fld; 
            }

        }
    } 

    // 공백 검사후 공백을 "" 로 변환
    function wrestNospace(fld)
    {
        var pattern = /(\s)/g; // \s 공백 문자
        if (pattern.test(fld.value)) 
        {
            if (wrestFld == null) 
            {
                wrestMsg = wrestItemname(fld) + " : 공백이 없어야 합니다.\n";
                wrestFld = fld;
            }
        }
    }

    // submit 할 때 속성을 검사한다.
    function wrestSubmit()
    {
        wrestMsg = "";
        wrestFld = null;

        var attr = null;

        // 해당폼에 대한 요소의 갯수만큼 돌려라
        for (var i = 0; i < this.elements.length; i++) 
        {
            // Input tag 의 type 이 text, file, password 일때만
            // 3.30
            // 셀렉트 박스일때도 필수 선택 검사합니다. select-one
            if (this.elements[i].type == "text" || 
                this.elements[i].type == "file" || 
                this.elements[i].type == "password" ||
                this.elements[i].type == "select-one" ||
				this.elements[i].type == "radio" ||
                this.elements[i].type == "textarea") 
            {
                // 배열의 길이만큼 돌려라
                for (var j = 0; j < arrAttr.length; j++) 
                {
                    // 배열에 정의한 속성과 비교해서 속성이 있거나 값이 있다면
                    if (wrestCheckAttrExist(this.elements[i], arrAttr[j]) != null) 
                    {
                        /*
                        // 기본 색상으로 돌려놓고
                        if (this.elements[i].getAttribute("required") != null) {
                            this.elements[i].style.backgroundColor = wrestFldDefaultColor;
                        }
                        */
                        switch (arrAttr[j]) 
                        {
                            case "required"     : wrestRequired(this.elements[i]); break;
							case "afterinput"	: wrestAfterInput(this.elements[i]);break;
							case "afterradio"	: wrestAfterRadio(this.elements[i]);break;
							case "afterselect"	: wrestAfterSelect(this.elements[i], this);break;
							case "aftercheckbox"	: wrestAfterCheckbox(this.elements[i], this);break;
                            case "trim"         : wrestTrim(this.elements[i]); break;
                            case "minlength"    : wrestMinlength(this.elements[i]); break;
                            case "email"        : wrestEmail(this.elements[i]); break;
                            case "hangul"       : wrestHangul(this.elements[i]); break;
                            case "hangul2"      : wrestHangul2(this.elements[i]); break;
                            case "hangulalphanumeric"      
                                                : wrestHangulAlphaNumeric(this.elements[i]); break;
							case "hangulalphacap"      
                                                : wrestHangulAlphaCap(this.elements[i]); break;
							case "hangulnumeric"      
                                                : wrestHangulNumeric(this.elements[i]); break;
                            case "memberid"     : wrestMemberId(this.elements[i]); break;
							case "passwd"     : wrestPasswd(this.elements[i]); break;
                            case "nospace"      : wrestNospace(this.elements[i]); break;
                            case "numeric"      : wrestNumeric(this.elements[i]); break; 
							case "numericfloat"      : wrestNumericFloat(this.elements[i]); break; 
							case "minusnumeric"      : wrestMinusNumeric(this.elements[i]); break; 
							case "minusnumericfloat"      : wrestMinusNumericFloat(this.elements[i]); break; 
							case "overzero"    : wrestOverZero(this.elements[i]); break; 
							case "undernum"    : wrestUnderNum(this.elements[i]); break;
                            case "alpha"        : wrestAlpha(this.elements[i]); break; 
                            case "alphanumeric" : wrestAlphaNumeric(this.elements[i]); break; 
                            case "alphanumericunderline" : 
                                                  wrestAlphaNumericUnderLine(this.elements[i]); break; 
                            case "jumin"        : wrestJumin(this.elements[i]); break; 
                            case "saupja"       : wrestSaupja(this.elements[i]); break; 
							
							// 김선용 2006.3 - 전화번호 형식 검사
							case "telnumber"	: wrestTelnumber(this.elements[i]); break;
                            default : break;
                        }
                    }
                }
            }
        }

        // 필드가 null 이 아니라면 오류메세지 출력후 포커스를 해당 오류 필드로 옮김
        // 오류 필드는 배경색상을 바꾼다.
        if (wrestFld != null) 
        { 
            alert(wrestMsg); 
            if (wrestFld.style.display != 'none') 
            { 
                wrestFld.style.backgroundColor = wrestFldBackColor; 
                wrestFld.focus(); 
            } 
            return false; 
        } 

        if (this.oldsubmit && this.oldsubmit() == false)
            return false;

        return true;
    }

    function wrestSubmit2(f)
    {
        wrestMsg = "";
        wrestFld = null;

        var attr = null;

        // 해당폼에 대한 요소의 갯수만큼 돌려라
        for (var i = 0; i < f.elements.length; i++) 
        {
            // Input tag 의 type 이 text, file, password 일때만
            // 3.30
            // 셀렉트 박스일때도 required 선택 검사합니다. select-one
            if (f.elements[i].type == "text" || 
                f.elements[i].type == "file" || 
                f.elements[i].type == "password" ||
                f.elements[i].type == "select-one" ||
				f.elements[i].type == "radio" ||
                f.elements[i].type == "textarea") 
            {
                // 배열의 길이만큼 돌려라
                for (var j = 0; j < arrAttr.length; j++) 
                {
                    // 배열에 정의한 속성과 비교해서 속성이 있거나 값이 있다면
                    if (wrestCheckAttrExist(f.elements[i], arrAttr[j]) != null) 
                    {
                        /*
                        // 기본 색상으로 돌려놓고
                        if (f.elements[i].getAttribute("required") != null) {
                            f.elements[i].style.backgroundColor = wrestFldDefaultColor;
                        }
                        */
                        switch (arrAttr[j]) 
                        {
                            case "required"     : wrestRequired(f.elements[i]); break;
							case "afterinput"	: wrestAfterInput(f.elements[i]);break;
							case "afterradio"	: wrestAfterRadio(f.elements[i]);break;
							case "afterselect"	: wrestAfterSelect(f.elements[i], f);break;
							case "aftercheckbox"	: wrestAfterCheckbox(f.elements[i], f);break;
                            case "trim"         : wrestTrim(f.elements[i]); break;
                            case "minlength"    : wrestMinlength(f.elements[i]); break;
                            case "email"        : wrestEmail(f.elements[i]); break;

							case "email1"        : wrestEmail1(f.elements[i]); break;
							case "email2"        : wrestEmail2(f.elements[i]); break;

                            case "hangul"       : wrestHangul(f.elements[i]); break;
                            case "hangul2"      : wrestHangul2(f.elements[i]); break;
                            case "hangulalphanumeric"      
                                                : wrestHangulAlphaNumeric(f.elements[i]); break;
							case "hangulalphacap"      
                                                : wrestHangulAlphaCap(f.elements[i]); break;
							case "hangulnumeric"      
                                                : wrestHangulNumeric(f.elements[i]); break;
                            case "memberid"     : wrestMemberId(f.elements[i]); break;
							case "passwd"     : wrestPasswd(f.elements[i]); break;
                            case "nospace"      : wrestNospace(f.elements[i]); break;
                            case "numeric"      : wrestNumeric(f.elements[i]); break; 
							case "numericfloat"      : wrestNumericFloat(f.elements[i]); break; 
							case "minusnumeric"      : wrestMinusNumeric(f.elements[i]); break; 
							case "minusnumericfloat"      : wrestMinusNumericFloat(f.elements[i]); break; 
							case "overzero"    : wrestOverZero(f.elements[i]); break; 
							case "undernum"    : wrestUnderNum(f.elements[i]); break;
                            case "alpha"        : wrestAlpha(f.elements[i]); break; 
                            case "alphanumeric" : wrestAlphaNumeric(f.elements[i]); break; 
							case "lowalphanumeric" : wrestLowAlphaNumeric(f.elements[i]); break; 
                            case "alphanumericunderline" : 
                                                  wrestAlphaNumericUnderLine(f.elements[i]); break; 
                            case "jumin"        : wrestJumin(f.elements[i]); break; 
                            case "saupja"       : wrestSaupja(f.elements[i]); break; 
							
							// 김선용 2006.3 - 전화번호 형식 검사
							case "telnumber"	: wrestTelnumber(f.elements[i]); break;
                            default : break;
                        }
                    }
                }
            }
        }

        // 필드가 null 이 아니라면 오류메세지 출력후 포커스를 해당 오류 필드로 옮김
        // 오류 필드는 배경색상을 바꾼다.
        if (wrestFld != null) 
        { 
            alert(wrestMsg); 
            if (wrestFld.style.display != 'none') 
            { 
                wrestFld.style.backgroundColor = wrestFldBackColor; 
                wrestFld.focus(); 
            } 
            return false; 
        } 

//        if (this.oldsubmit && this.oldsubmit() == false)
//            return false;

        return true;
    }

    // 초기에 onsubmit을 가로채도록 한다.
    function wrestInitialized()
    {
        for (var i = 0; i < document.forms.length; i++) 
        {
            // onsubmit 이벤트가 있다면 저장해 놓는다.
            if (document.forms[i].onsubmit) document.forms[i].oldsubmit = document.forms[i].onsubmit;
            document.forms[i].onsubmit = wrestSubmit;
            for (var j = 0; j < document.forms[i].elements.length; j++) 
            {
                // 필수 입력일 경우는 * 배경이미지를 준다.
                if (wrestCheckAttrExist(document.forms[i].elements[j], 'required') != null && wrestCheckAttrExist(document.forms[i].elements[j], 'required_no') == null) 
                {
                    //document.forms[i].elements[j].style.backgroundColor = wrestFldBackColor;
                    //document.forms[i].elements[j].className = "wrest_required";
                    //document.forms[i].elements[j].style.backgroundImage = "url(/js/wrest.gif)";
                    //document.forms[i].elements[j].style.backgroundPosition = "top right";
                    //document.forms[i].elements[j].style.backgroundRepeat = "no-repeat";
                }
            }
        }
    }

    wrestInitialized();
}
