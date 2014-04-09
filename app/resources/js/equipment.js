$(function() {
	var currentMenu;

	$("#content > div.equipment-item > ul.menu").menu();
	$("#content > div.equipment-item").click(function(e) {
		if(currentMenu) {
			currentMenu.hide();
		}
		currentMenu = $(this).find('ul.menu');
		currentMenu.show();

		event.stopPropagation();
	});

	$(document).on("click", function(e) {
		currentMenu.hide();
		currentMenu = null;
	});
});