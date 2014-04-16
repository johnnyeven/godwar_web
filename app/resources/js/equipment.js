$(function() {
	var currentMenu;

	$("#content").tooltip({
		items: "div.equipment-item",
		content: function() {
			var element = $(this).find("div.equipment-item-detail");
			return element.html();
		},
		track: true
	});

	$("#content > div.equipment-item > ul.menu").menu();
	$("#content > div.equipment-item").click(function(e) {
		$("#content").tooltip("disable");

		if(currentMenu) {
			currentMenu.hide();
		}
		currentMenu = $(this).find('ul.menu');
		currentMenu.show();

		event.stopPropagation();
	});
	$("#content > div.equipment-item > ul.menu > li").click(function(e) {
		$("#content").tooltip("enable");
		currentMenu.hide();
		currentMenu = null;
		e.stopPropagation();
	});

	$(document).on("click", function(e) {
		$("#content").tooltip("enable");
		if(currentMenu) {
			currentMenu.hide();
			currentMenu = null;
		}
	});
});