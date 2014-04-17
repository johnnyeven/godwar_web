$(function() {
	var current_item, current_menu;

	$("#dialog_message").dialog({
		autoOpen: false,
		modal: true,
		width: 400,
		buttons: [
			{
				text: "Ok",
				click: function() {
					$( this ).dialog( "close" );
				}
			}
		]
	});

	$("#market_list").contextmenu(function() {
		return false;
	});
	$("#market_list").tooltip({
		items: "tbody > tr > td > span.name",
		content: function() {
			var element = $(this).parent().find("div.equipment-item-detail");
			return element.html();
		},
		track: true
	});

	$("#market_list > tbody > tr > td > ul.menu").menu();

	$("#market_list > tbody > tr").mouseup(function(e) {
		if(e.which == 3) {
			if(current_item) {
				current_item.removeClass('market-item-current');
			}
			current_item = $(this);
			current_item.addClass('market-item-current');

			$("#content").tooltip("disable");

			if(current_menu) {
				current_menu.hide();
			}
			current_menu = $(this).find("td:first > ul.menu");
			var top = e.clientY + $(document).scrollTop();
			var left = e.clientX + $(document).scrollLeft();

			current_menu.css({
				"top": top,
				"left": left
			});
			current_menu.show();
		}
		e.stopPropagation();
	});
	$("#market_list > tbody > tr").click(function(e) {
		$("#market_list").tooltip("enable");
		if(current_item) {
			current_item.removeClass('market-item-current');
		}
		current_item = $(this);
		current_item.addClass('market-item-current');

		if(current_menu) {
			current_menu.hide();
			current_menu = null;
		}

		e.stopPropagation();
	});

	$("#market_list > tbody > tr > td > ul.menu > li").click(function() {
		$("#market_list").tooltip("enable");
		current_menu.hide();
		current_menu = null;
		return false;
	});

	$("#market_list > tbody > tr > td > ul.menu > li > a.cancel").click(function() {
		if(!current_item) {
			current_item = $("#market_list > tbody > tr");
		}
		var id = current_item.find("td:first > span.id").text();
		request_cancel(id);
	});

	$("#market_list > tbody > tr > td > ul.menu > li > a.buy").click(function() {
		if(!current_item) {
			current_item = $("#market_list > tbody > tr");
		}
		var id = current_item.find("td:first > span.id").text();
		request_buy(id);
	});

	$(document).click(function(e) {
		$("#market_list").tooltip("enable");
		if(current_item) {
			current_item.removeClass('market-item-current');
			current_item = null;
		}
		if(current_menu) {
			current_menu.hide();
			current_menu = null;
		}
	});

	var request_cancel = function(id) {
		if(id) {
			var parameter = {
				"id": id
			};
			$.post('market/cancel', parameter, onCancel);
		}
	};

	var request_buy = function(id) {
		if(id) {
			var parameter = {
				"id": id
			};
			$.post('market/buy_submit', parameter, onBuy);
		}
	};

	var onBuy = function(data) {
		if(data.code == MARKET_BUY_SUCCESS) {
			var id = data.params.id;
			var item = $("#market_list").find('tbody > tr > td > span.id:contains("' + id + '")').parent().parent();
			if(item.length > 0) {
				item.remove();
			}
			$("#dialog_alert").find("p > strong").text("已完成购买订单 " + data.params.name);
			$("#dialog_alert").fadeIn();
			setTimeout(function() {
				$("#dialog_alert").fadeOut();
			}, 3000);
		} else {
			$("#dialog_message_content").text(data.code);
			$("#dialog_message").dialog("open");
		}
	};

	var onCancel = function(data) {
		if(data.code == MARKET_CANCEL_SUCCESS) {
			var id = data.params.id;
			var item = $("#market_list").find('tbody > tr > td > span.id:contains("' + id + '")').parent().parent();
			if(item.length > 0) {
				item.remove();
			}
			$("#dialog_alert").find("p > strong").text("已取消订单 " + data.params.name);
			$("#dialog_alert").fadeIn();
			setTimeout(function() {
				$("#dialog_alert").fadeOut();
			}, 3000);
		} else {
			$("#dialog_message_content").text(data.code);
			$("#dialog_message").dialog("open");
		}
	};
});