$(function() {
	var currentMenu;
	var id, name, current_item, current_count;

	$("#content").tooltip({
		items: "div.equipment-item",
		content: function() {
			var element = $(this).find("div.equipment-item-detail");
			return element.html();
		},
		track: true
	});

	$("#market_sell_form").dialog({
		autoOpen: false,
		modal: true,
		width: 400,
		buttons: [
			{
				text: "Ok",
				click: function() {
					request_market_sell(id);
					$( this ).dialog( "close" );
				}
			},
			{
				text: "Cancel",
				click: function() {
					$( this ).dialog( "close" );
				}
			}
		]
	});

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
	$("#content > div.equipment-item > ul.menu > li > a.market_sell").click(function() {
		var item = $(this).parent().parent().parent();
		id = item.find('span.id').text();
		name = item.find('span.name').text();
		current_item = item.find("div.equipment-item-detail");

		$("#dialog_item_name").text(name);
		$("#market_sell_form > div.dialog-item-property").html(current_item.html());
		$("#market_sell_form").dialog("open");

		return false;
	});

	$(document).on("click", function(e) {
		$("#content").tooltip("enable");
		if(currentMenu) {
			currentMenu.hide();
			currentMenu = null;
		}
	});

	$("#dialog_market_sell_sprice").keyup(function() {
		$("#dialog_market_sell_price").val($("#dialog_market_sell_sprice").val());
	});

	var request_market_sell = function(i) {
		if(i)
		{
			var parameter = {
				"id": i,
				'type': 1,
				'price': $("#dialog_market_sell_price").val(),
				'endtime': $("#dialog_market_sell_endtime").val()
			};
			$.post("../action/market/sell_submit", parameter, onItemMarketSold);
		}
	};

	var onItemMarketSold = function(data) {
		if(data.code == EQUIPMENT_MARKET_SELL_SUCCESS) {
			console.log(data);
			var i = data.params.id;
			var item = $("#content").find('div.equipment-item > span.id:contains("' + id + '")').parent();
			if(item.length > 0) {
				item.remove();
			}
			$("#dialog_alert").find("p > strong").text("成功寄售装备 " + data.params.name);
			$("#dialog_alert").fadeIn();
			setTimeout(function() {
				$("#dialog_alert").fadeOut();
			}, 3000);
		} else {
			$("#dialog_message_content").text(data.code);
			$("#dialog_message").dialog("open");
		}
	}
});