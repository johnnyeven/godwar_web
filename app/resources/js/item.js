$(function() {
	var currentMenu;
	var id, name, current_count;

	$("#content > div.equipment-item > ul.menu").menu();
	$("#content > div.equipment-item").click(function(e) {
		if(currentMenu) {
			currentMenu.hide();
		}
		currentMenu = $(this).find('ul.menu');
		currentMenu.show();

		event.stopPropagation();
	});

	$("#content > div.equipment-item > ul.menu > li").click(function(e) {
		currentMenu.hide();
		currentMenu = null;
		e.stopPropagation();
	});

	$(document).on("click", function(e) {
		if(currentMenu) {
			currentMenu.hide();
			currentMenu = null;
		}
	});

	$("#content > div.equipment-item > ul.menu > li > a.sell").click(function() {
		var item = $(this).parent().parent().parent();
		id = item.find('span.id').text();
		name = item.find('span.name').text();
		current_count = parseInt(item.find('span.count').text());

		$("#dialog_item_name").text(name);
		$("#dialog_item_count").val(current_count);
		$("#dialog_form").dialog("open");
	});

	$("#content > div.equipment-item > ul.menu > li > a.learn_blueprint").click(function() {
		var item = $(this).parent().parent().parent();
		id = item.find('span.id').text();
		name = item.find('span.name').text();

		var parameter = {
			"id": id
		};
		$.post('../action/alchemy/learn', parameter, onItemLearned);
	});

	$("#content > div.equipment-item > ul.menu > li > a.use").click(function() {
		var item = $(this).parent().parent().parent();
		id = item.find('span.id').text();
		name = item.find('span.name').text();

		var parameter = {
			"id": id
		};
		$.post('item/apply', parameter, onItemApply);
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

	var onItemSold = function(data) {
		if(data.code == ITEM_SELL_SUCCESS) {
			var i = data.params.id;
			var c = data.params.count
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
		console.log(data);
	}
});