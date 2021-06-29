$(document).ready(function() {
	$(".faq > li .question").click(function(){
		if($(this).parent().hasClass('on') == true){
			$(".faq > li").removeClass('on');
		} else {
			$(".faq > li").removeClass('on');
			$(this).parent().addClass('on');
		}
	});

	$('.heart.after').click(function() {
		if (!$(this).hasClass('on')) {
			$(this).addClass('on');
		}else{
			$(this).removeClass('on');
		}
	});

	$('.gearing').click(function() {
		if (!$(this).hasClass('on')) {
			$(this).addClass('on');
		}else{
			$(this).removeClass('on');
		}
	});	

	$('.heart').click(function() {
		if (!$(this).hasClass('on')) {
			$(this).addClass('on');
		}else{
			$(this).removeClass('on');
		}
	});

	$('.uix-button.toggle-settings').click(function() {
		if (!$(this).hasClass('color-primary')) {
			$(this).addClass('color-primary');
		}else{
			$(this).removeClass('color-primary');
		}
	});

	$('.gnb a.all').click(function() {
		if (!$('#all-category').hasClass('on')) {
			$('#all-category').addClass('on');
		}else{
			$('#all-category').removeClass('on');
		}
	});

	$('#all-category .close').click(function() {
		if (!$('#all-category').hasClass('on')) {
			$('#all-category').addClass('on');
		}else{
			$('#all-category').removeClass('on');
		}
	});

	$('.type1').click(function() {
		if (!$(this).hasClass('on')) {
			$(this).addClass('on');
			$('.type2').removeClass('on');
			$('.gallery-list').addClass('on');	
			$('.gallery-list-type2').removeClass('on');
		}
	});

	$('.type2').click(function() {
		if (!$(this).hasClass('on')) {
			$(this).addClass('on');
			$('.type1').removeClass('on');
			$('.gallery-list-type2').addClass('on');
			$('.gallery-list').removeClass('on');
		}
	});
	
	$('.product-view-detail .tab-menu li .uix-button').click(function() {
		$('.product-view-detail .tab-menu li .uix-button').removeClass('on');
		if (!$(this).hasClass('on')) {
			$(this).addClass('on');
		}
	});

	$('.product-view-detail .tab-menu .uix-button.tab1').click(function() {
		$('.product-view-detail .view-detail').removeClass('on');
		if (!$('.product-view-detail .view-detail.tab1').hasClass('on')) {
			$('.product-view-detail .view-detail.tab1').addClass('on');
		}
	});

	$('.product-view-detail .tab-menu .uix-button.tab2').click(function() {
		$('.product-view-detail .view-detail').removeClass('on');
		if (!$('.product-view-detail .view-detail.tab2').hasClass('on')) {
			$('.product-view-detail .view-detail.tab2').addClass('on');
		}
	});

	$('.product-view-detail .tab-menu .uix-button.tab3').click(function() {
		$('.product-view-detail .view-detail').removeClass('on');
		if (!$('.product-view-detail .view-detail.tab3').hasClass('on')) {
			$('.product-view-detail .view-detail.tab3').addClass('on');
		}
	});

	$('.product-view-detail .tab-menu .uix-button.tab4').click(function() {
		$('.product-view-detail .view-detail').removeClass('on');
		if (!$('.product-view-detail .view-detail.tab4').hasClass('on')) {
			$('.product-view-detail .view-detail.tab4').addClass('on');
		}
	});

	$('.product-view-detail .tab-menu .uix-button.tab5').click(function() {
		$('.product-view-detail .view-detail').removeClass('on');
		if (!$('.product-view-detail .view-detail.tab5').hasClass('on')) {
			$('.product-view-detail .view-detail.tab5').addClass('on');
		}
	});

	/*
	$('.recent-shipping button').click(function() {
		$('.recent-shipping button').removeClass('on');
		if (!$(this).hasClass('on')) {
			$(this).addClass('on');
		}
	});

	$('.recent-shipping button.n1').click(function() {
		$('.recent-shipping-info').removeClass('on');
		if (!$('.recent-shipping-info.n1').hasClass('on')) {
			$('.recent-shipping-info.n1').addClass('on');
		}
	});

	$('.recent-shipping button.n2').click(function() {
		$('.recent-shipping-info').removeClass('on');
		if (!$('.recent-shipping-info.n2').hasClass('on')) {
			$('.recent-shipping-info.n2').addClass('on');
		}
	});

	$('.recent-shipping button.n3').click(function() {
		$('.recent-shipping-info').removeClass('on');
		if (!$('.recent-shipping-info.n3').hasClass('on')) {
			$('.recent-shipping-info.n3').addClass('on');
		}
	});
	*/

	$('.gnb a.all').click(function() {
		if (!$(this).hasClass('on')) {
			$(this).addClass('on');
		}else{
			$(this).removeClass('on');
		}
	});		
	
});

function SwitchMenu(obj){
	var el = document.getElementById(obj);
	if(el.style.display != "block"){								
		el.style.display = "block";
	}else{
		el.style.display = "none";
	}
}

function ViewObj(obj,arg){
	var el = document.getElementById(obj);
	if(arg=='1'){		
		el.style.display = "block";
	}else if(arg=='0'){		
		el.style.display = "none";
	}
}


function checkLength(f, n, note){
	var limit = n; 
	var StrLen = f.value.length;
	if(StrLen > limit){
		alert("내용은 "+limit+"까지만 입력이 가능합니다.");
		f.value = f.value.substring(0,limit);
		f.focus();
	}
	document.getElementById(note).innerHTML = f.value.length;
}
