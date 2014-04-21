$(function() {
	var timer;
	var result;

	$("#btnConfirm > button").click(function() {
		$(this).attr("disabled", "disabled");
		$("#progress").show();
		timer = setInterval(progress, 80);
		$.post("rebirth/process", {}, onComplete);
	});

	var percent = 0;

	var progress = function() {
		if(percent < 100) {
			percent++;
		} else {
			clearInterval(timer);
			success();
		}
		$("#progress_bar > div.bar").width(percent + "%");
		$("#percentage").text(percent + "%");
	};

	var success = function() {
		if(result) {
			$("#info").text("现在，你已经重生了！");
		}
	};

	var onComplete = function(data) {
		if(data && data.code == REBIRTH_SUCCESS) {
			result = true;
		} else {
			
		}
	}
});