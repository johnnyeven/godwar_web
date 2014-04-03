$(function() {
	$("#blueprint_list > p").click(function() {
		var id = $(this).attr("rel");
		var block_id = "alchemy_item_" + id;
		if($("#" + block_id).length > 0) {
			$("#content > div.detail").hide();
			$("#" + block_id).show();
		} else {
			var parameter = {
				"id": id
			};
			$.post('alchemy/info', parameter, onAlchemyInfo);
		}
	});

	$(document).on("click", "#content > div.detail > a.alchemy_start", function(e) {
		var id = $(this).parent().find("span.alchemy_item_id").text();
		var parameter = {
			"id": id
		};
		$.post('alchemy/build', parameter, onAlchemyBuild);
		return false;
	});

	$(document).on("click", "#queue > div.queue_item > span.queue_control > a.queue_complete", function(e) {
		var id = $(this).attr('rel');
		var parameter = {
			"id": id
		};
		$.post('alchemy/receive', parameter, onAlchemyReceive);
		return false;
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

	var onAlchemyInfo = function(data) {
		if(data.code == ALCHEMY_INFO_SUCCESS) {
			var result = data.params;
			var html = '<div class="detail" id="alchemy_item_' + result.id + '">';
			html += '<span class="alchemy_item_id" style="display:none;">' + result.id + '</span>';
			html += '<h3>' + result.name + '</h3>';
			html += '<p>' + result.comment + '</p>';
			html += '<ul class="materials">';

			var item;
			var count;
			for(var i in result.materials) {
				item = $('#item > div.item_detail > span.item_id:contains("' + result.materials[i].id + '")');
				if(item.length > 0) {
					count = parseInt(item.next().text());
					if(count >= result.materials[i].cost) {
						html += '<li style="color:#2BD52B;">';
					} else {
						html += '<li style="color:#CC00FF;">';
					}
					html += '<span style="display:none;" class="material_id">' + result.materials[i].id + '</span><span class="material_name">' + result.materials[i].name + '</span> x<span class="material_cost">' + result.materials[i].cost + '(' + count + ')</span></li>';
				} else {
					html += '<li style="color:#FF0000;"><span style="display:none;" class="material_id">' + result.materials[i].id + '</span><span class="material_name">' + result.materials[i].name + '</span> x<span class="material_cost">' + result.materials[i].cost + '</span></li>';
				}
			}
			html += '</ul>';
			html += '<div class="product">';
			html += '<h3>产出 ' + result.product.name + '</h3>';
			html += '<p>' + result.product.comment + '</p>';
			html += '</div>';
			html += '<a class="alchemy_start" href="#">立即制作</a>';
			html += '</div>';

			$("#content > div.detail").hide();
			$("#content").append(html);
		} else {
			$("#dialog_message_content").text(data.code);
			$("#dialog_message").dialog("open");
		}
	};

	var onAlchemyBuild = function(data) {
		if(data.code == ALCHEMY_BUILD_SUCCESS) {
			var starttime = getLocalTime(data.params.starttime);
			var endtime = getLocalTime(data.params.endtime);
			var html = '<div class="queue_item" id="queue_item_' + data.params.id + '">';
			html += '<span class="queue_name">' + data.params.name + '</span>';
			html += ' | <span class="queue_starttime">' + starttime + '</span>';
			html += ' | <span class="queue_endtime">' + endtime + '</span>';
			html += '</div>';
			$("#queue").prepend(html);
			$("#dialog_alert").find("p > strong").text("开始合成 " + data.params.name);
			$("#dialog_alert").fadeIn();
			setTimeout(function() {
				$("#dialog_alert").fadeOut();
			}, 3000);
		} else {
			$("#dialog_message_content").text(data.code);
			$("#dialog_message").dialog("open");
		}
	}

	var onAlchemyReceive = function(data) {
		if(data.code == ALCHEMY_RECEIVE_SUCCESS) {
			var id = data.params.id;
			$("#queue_item_" + id).remove();
			$("#dialog_alert").find("p > strong").text("已接收 " + data.params.name + " x1");
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

function getLocalTime(nS) { 
	return new Date(parseInt(nS) * 1000).toLocaleString().replace(/年|月/g, "-").replace(/日/g, " "); 
} 