$(function() {
	var current_menu;
	var id, name, current_item;

	$("#content").contextmenu(function() {
		return false;
	});
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

	$("#content > div.equipment-item").mouseup(function(e) {
		if(e.which == 3) {
			if(current_item) {
				current_item.removeClass('equipment-item-current');
			}
			current_item = $(this);
			current_item.addClass('equipment-item-current');

			$("#content").tooltip("disable");
			if(current_menu) {
				current_menu.hide();
			}
			current_menu = $(this).find('ul.menu');
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
	$("#content > div.equipment-item").click(function(e) {
		$("#content").tooltip("enable");
		if(current_item) {
			current_item.removeClass('equipment-item-current');
		}
		current_item = $(this);
		current_item.addClass('equipment-item-current');

		if(current_menu) {
			current_menu.hide();
			current_menu = null;
		}
		
		e.stopPropagation();
	});
	$("#content > div.equipment-item > ul.menu > li").click(function(e) {
		$("#content").tooltip("enable");
		current_menu.hide();
		current_menu = null;
		e.stopPropagation();
	});
	$("#content > div.equipment-item > ul.menu > li > a.market_sell").click(function() {
		if(!current_item) {
			current_item = $(this).parent().parent().parent();
		}
		id = current_item.find('span.id').text();
		name = current_item.find('span.name').text();

		$("#dialog_item_name").text(name);
		$("#market_sell_form > div.dialog-item-property").html(current_item.find("div.equipment-item-detail").html());
		$("#market_sell_form").dialog("open");
	});

	$(document).click(function(e) {
		$("#content").tooltip("enable");
		if(current_item) {
			current_item.removeClass('equipment-item-current');
			current_item = null;
		}
		if(current_menu) {
			current_menu.hide();
			current_menu = null;
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
				'count': 1,
				'price': $("#dialog_market_sell_price").val(),
				'endtime': $("#dialog_market_sell_endtime").val()
			};
			$.post("../action/market/sell_submit", parameter, onItemMarketSold);
		}
	};

	var onItemMarketSold = function(data) {
		if(data.code == EQUIPMENT_MARKET_SELL_SUCCESS) {
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