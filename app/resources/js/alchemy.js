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
		console.log(data);
	}
});