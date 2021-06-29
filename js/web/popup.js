function closePopup(arg) {
    $('#popup_' + arg).hide();
}

function closePopup24(arg) {
	$.cookie('popup_' + arg, 'done', { path: '/', expires: 1 });
    $('#popup_' + arg).hide();
}