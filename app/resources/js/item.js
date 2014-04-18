$(function() {
	var current_menu;
	var id, name, current_item, current_count;

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
		$("#content").tooltip("disable");
		current_menu.hide();
		current_menu = null;
		
		return false;
	});

	$("#content > div.equipment-item > ul.menu > li > a.sell").click(function() {
		if(!current_item) {
			current_item = $(this).parent().parent().parent();
		}
		id = current_item.find('span.id').text();
		name = current_item.find('span.name').text();
		current_count = parseInt(current_item.find('span.count').text());

		$("#dialog_item_name").text(name);
		$("#dialog_item_count").val(current_count);
		$("#dialog_form").dialog("open");
	});

	$("#content > div.equipment-item > ul.menu > li > a.market_sell").click(function() {
		if(!current_item) {
			current_item = $(this).parent().parent().parent();
		}
		id = current_item.find('span.id').text();
		name = current_item.find('span.name').text();
		current_count = parseInt(current_item.find('span.count').text());

		$("#dialog_item_name").text(name);
		$("#market_sell_form > div.dialog-item-property").html(current_item.find("div.equipment-item-detail").html());
		$("#dialog_market_sell_count").val(current_count);
		$("#market_sell_form").dialog("open");
	});

	$("#content > div.equipment-item > ul.menu > li > a.learn_blueprint").click(function() {
		if(!current_item) {
			current_item = $(this).parent().parent().parent();
		}
		id = current_item.find('span.id').text();
		name = current_item.find('span.name').text();

		var parameter = {
			"id": id
		};
		$.post('../action/alchemy/learn', parameter, onItemLearned);
	});

	$("#content > div.equipment-item > ul.menu > li > a.use").click(function() {
		if(!current_item) {
			current_item = $(this).parent().parent().parent();
		}
		id = current_item.find('span.id').text();
		name = current_item.find('span.name').text();

		var parameter = {
			"id": id
		};
		$.post('item/apply', parameter, onItemApply);
	});

	$("#content > div.equipment-item > ul.menu > li > a.lock").click(function() {
		if(!current_item) {
			current_item = $(this).parent().parent().parent();
		}
		id = current_item.find('span.id').text();
		name = current_item.find('span.name').text();

		var parameter = {
			"id": id
		};
		$.post('item/lock', parameter, onItemLocked);
	});

	$("#content > div.equipment-item > ul.menu > li > a.unlock").click(function() {
		if(!current_item) {
			current_item = $(this).parent().parent().parent();
		}
		id = current_item.find('span.id').text();
		name = current_item.find('span.name').text();

		var parameter = {
			"id": id
		};
		$.post('item/unlock', parameter, onItemUnlocked);
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

	$("#dialog_form").dialog({
		autoOpen: false,
		modal: true,
		width: 400,
		buttons: [
			{
				text: "Ok",
				click: function() {
					var count = parseInt($("#dialog_item_count").val());
					if(count > current_count) {
						$("#dialog_message_content").text("输入的数量超过了当前物品的持有量");
						$("#dialog_message").dialog("open");
					} else {
						request_sell(id, count);
						$( this ).dialog( "close" );
						$("#dialog_item_name").text("");
						$("#dialog_item_count").val("");
					}
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

	$("#dialog_market_sell_sprice").keyup(function() {
		var sprice = $("#dialog_market_sell_sprice").val();
		var count = $("#dialog_market_sell_count").val();
		if(!sprice) sprice = 0;
		if(!count) count = 0;
		$("#dialog_market_sell_price").val(parseInt(sprice) * parseInt(count));
	});

	$("#dialog_market_sell_count").keyup(function() {
		var sprice = $("#dialog_market_sell_sprice").val();
		var count = $("#dialog_market_sell_count").val();
		if(!sprice) sprice = 0;
		if(!count) count = 0;
		$("#dialog_market_sell_price").val(parseInt(sprice) * parseInt(count));
	});

	var request_sell = function(i, c) {
		if(i && c > 0)
		{
			var parameter = {
				"id": i,
				"count": c
			};
			$.post("item/sell", parameter, onItemSold);
		}
	};

	var request_market_sell = function(i) {
		if(i)
		{
			var parameter = {
				"id": i,
				'type': 2,
				'count': $("#dialog_market_sell_count").val(),
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
			$("#dialog_alert").find("p > strong").text("成功寄售物品 " + data.params.name);
			$("#dialog_alert").fadeIn();
			setTimeout(function() {
				$("#dialog_alert").fadeOut();
			}, 3000);
		} else {
			$("#dialog_message_content").text(data.code);
			$("#dialog_message").dialog("open");
		}
	}

	var onItemSold = function(data) {
		if(data.code == ITEM_SELL_SUCCESS) {
			var i = data.params.id;
			var c = data.params.count;
			var item = $("#content").find('div.equipment-item > span.id:contains("' + id + '")').parent();
			if(item.length > 0) {
				if(data.params.remain == 0) {
					item.remove();
				} else {
					item.find("span.count").text(data.params.remain);
				}
			}
			$("#dialog_alert").find("p > strong").text("成功卖出物品 " + data.params.name + " x" + data.params.count + "，获得" + data.params.price + "金币");
			$("#dialog_alert").fadeIn();
			setTimeout(function() {
				$("#dialog_alert").fadeOut();
			}, 3000);
		} else {
			$("#dialog_message_content").text(data.code);
			$("#dialog_message").dialog("open");
		}
	}

	var onItemLearned = function(data) {
		if(data.code == ALCHEMY_LEARN_SUCCESS) {
			var i = data.params.id;
			var item = $("#content").find('div.equipment-item > span.id:contains("' + id + '")').parent();
			if(item.length > 0) {
				if(data.params.remain == 0) {
					item.remove();
				} else {
					item.find("span.count").text(data.params.remain);
				}
			}
			$("#dialog_alert").find("p > strong").text("成功学习新配发 " + data.params.name);
			$("#dialog_alert").fadeIn();
			setTimeout(function() {
				$("#dialog_alert").fadeOut();
			}, 3000);
		} else {
			$("#dialog_message_content").text(data.code);
			$("#dialog_message").dialog("open");
		}
	}

	var onItemApply = function(data) {
		if(data.code == ITEM_USE_SUCCESS) {
			var i = data.params.id;
			var item = $("#content").find('div.equipment-item > span.id:contains("' + id + '")').parent();
			if(item.length > 0) {
				if(data.params.remain == 0) {
					item.remove();
				} else {
					item.find("span.count").text(data.params.remain);
				}
			}
			$("#dialog_alert").find("p > strong").text("已使用 " + data.params.name);
			$("#dialog_alert").fadeIn();
			setTimeout(function() {
				$("#dialog_alert").fadeOut();
			}, 3000);
		} else {
			$("#dialog_message_content").text(data.code);
			$("#dialog_message").dialog("open");
		}
	}

	var onItemLocked = function(data) {
		if(data.code == ITEM_LOCK_SUCCESS) {
			var i = data.params.id;
			var item = $("#content").find('div.equipment-item > span.id:contains("' + id + '")').parent();
			if(item.length > 0) {
				item.find("ul.menu > li > a.lock").parent().hide();
				item.find("ul.menu > li > a.unlock").parent().show();
			}
			$("#dialog_alert").find("p > strong").text("已锁定 " + data.params.name);
			$("#dialog_alert").fadeIn();
			setTimeout(function() {
				$("#dialog_alert").fadeOut();
			}, 3000);
		} else {
			$("#dialog_message_content").text(data.code);
			$("#dialog_message").dialog("open");
		}
	}

	var onItemUnlocked = function(data) {
		if(data.code == ITEM_UNLOCK_SUCCESS) {
			var i = data.params.id;
			var item = $("#content").find('div.equipment-item > span.id:contains("' + id + '")').parent();
			if(item.length > 0) {
				item.find("ul.menu > li > a.unlock").parent().hide();
				item.find("ul.menu > li > a.lock").parent().show();
			}
			$("#dialog_alert").find("p > strong").text("已解锁 " + data.params.name);
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