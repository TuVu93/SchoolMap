var window_width = 0;
var window_height = 0;

function refresh_image() {
	var ww = window_width - 2;
	var wh = window_height - 2;
	
	document.getElementById("leftCont").style.height="30px";
	/*$('#leftCont').css('height', wh + 'px');*/
}

function window_resize() { // called when window is resized
	window_width = $(window).width();
	window_height = $(window).height();
	refresh_image();
}

$(document).ready(function() {
	window_resize();
	$(window).bind('resize', function() { window_resize(); });
}