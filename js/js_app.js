// iOS8 관련한 object-c 호출 함수
var sendObjectMessage = function(parameters) {
	// ios10 대응 2016-09-22
	var set_val = 'toApp://' + JSON.stringify(parameters);
	location.href=set_val;
	/*
	var iframe = document.createElement('iframe');
	iframe.setAttribute('src', 'toApp://' + JSON.stringify(parameters));
	document.documentElement.appendChild(iframe);
	iframe.parentNode.removeChild(iframe);
	iframe = null;
	*/
};