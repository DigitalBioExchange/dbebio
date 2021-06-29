jQuery(document).ready(function($) {


	function onlyNumber(event){
		event = event || window.event;
		var keyID = (event.which) ? event.which : event.keyCode;
		if ( (keyID >= 48 && keyID <= 57) || (keyID >= 96 && keyID <= 105) || keyID == 8 || keyID == 46 || keyID == 37 || keyID == 39 ) 
			return;
		else
			return false;
	}
	function removeChar(event) {
		event = event || window.event;
		var keyID = (event.which) ? event.which : event.keyCode;
		if ( keyID == 8 || keyID == 46 || keyID == 37 || keyID == 39 ) 
			return;
		else
			event.target.value = event.target.value.replace(/[^0-9]/g, "");
	}

	/* 2021-02-25 추가 시작 */ 
	/* tab 관련 JS start */
	$(".tab-menu").each(function(index, thisTab){
		$(thisTab).find(".tab-header > li").on("click",function(){
			$(thisTab).find(".tab-header > li").removeClass("active");
			$(this).addClass("active");
			var hasContent = $(thisTab).hasClass(".tab-content");
			var activeIndex= $(thisTab).find(".tab-header > .active").index();
			$(thisTab).find(".tab-content > div").removeClass("active");
			$(thisTab).find(".tab-content > div").eq(activeIndex).addClass("active");
		});
	});
	/* tab 관련 JS end */


	/* 말줄임 관련 JS start */
	$('.clamp1').each(function(index, clampOne) {
		$clamp(clampOne, { clamp: 1 });
	});
		
	$('.clamp2').each(function(index, clampTwo) {
		$clamp(clampTwo, { clamp: 2 });
	});
		
	$('.clamp3').each(function(index, clampThree) {
		$clamp(clampThree, { clamp: 3 });
	});
	/* 말줄임 관련 JS end */
	/* 2021-02-25 추가 끝 */ 
	
});





