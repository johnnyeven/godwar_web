$(function() {
	$("#content > div.equipment").mouseover(function() {
		$(this).find('span.control').show();
	}).mouseout(function() {
		$(this).find('span.control').hide();
	});
});