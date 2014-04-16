$(function() {
	var run = false;
	var timerId = "";
	var remains = 0;
	var currentIndex = 0;
	var currentData;
	
	$("#btnStart").click(function() {
		if(!run) {
			run = true;
			start();
		}
	});

	$("#btnStop").click(function() {
		if(run) {
			run = false;
		}
		if(timerId) {
			self.clearInterval(timerId);
		}
	});
	
	function start() {
		if(run) {
			$.post("gather/request_gather", {}, show);
		}
	}
	
	function show(data) {
		if(data.code) {
			var code = parseInt(data.code);
			var html;
			switch(code) {
				case GATHER_ERROR_NOT_TIME:
					remains = data.params.next_battletime - data.params.timestamp;
					html = '<div class="post"><div class="entry color-purple">正在搜寻新的采集物...<em id="findTimer">(' + remains + ')</em></div></div>';
		        	$('#content').prepend(html);
		        	timerId = self.setInterval(checkTimer, 1000);
					break;
				case GATHER_ERROR_CONFLICT:
					html = '<div class="post"><div class="entry color-red">同一时刻只能进行一项行动，战斗或者采集，请刷新页面重试</div></div>';
					$('#content').prepend(html);
					if(run) {
						run = false;
					}
					if(timerId) {
						self.clearInterval(timerId);
					}
					break;
				case GATHER_ERROR_MAP_NOT_EXIST:
					html = '<div class="post"><div class="entry color-red">地图编号信息错误</div></div>';
					$('#content').prepend(html);
					if(run) {
						run = false;
					}
					if(timerId) {
						self.clearInterval(timerId);
					}
					break;
				case GATHER_ERROR_ITEM_NOT_EXIST:
					html = '<div class="post"><div class="entry color-red">物品编号信息错误</div></div>';
					$('#content').prepend(html);
					if(run) {
						run = false;
					}
					if(timerId) {
						self.clearInterval(timerId);
					}
					break;
				case GATHER_NOTHING:
					html = '<div class="post"><div class="entry">没有采到任何东西...</div></div>';
					$('#content').prepend(html);

					remains = data.params.next_battletime - data.params.timestamp;
					html = '<div class="post"><div class="entry color-purple">正在搜寻新的采集物...<em id="findTimer">(' + remains + ')</em></div></div>';
					$('#content').prepend(html);
		        	timerId = self.setInterval(checkTimer, 1000);
		        	break;
				case GATHER_SUCCESS:
					html = '<div class="post"><div class="entry">成功采集到 <span class="gather_name">' + data.params.name + '</span></div></div>';
					$('#content').prepend(html);

					remains = data.params.next_battletime - data.params.timestamp;
					html = '<div class="post"><div class="entry color-purple">正在搜寻新的采集物...<em id="findTimer">(' + remains + ')</em></div></div>';
					$('#content').prepend(html);
		        	timerId = self.setInterval(checkTimer, 1000);
					break;
			}
		}
	}
	
	function checkTimer() {
		if(remains > 1) {
			remains--;
			$("#findTimer").text("(" + remains + ")");
		} else {
			clearInfo();
			html = '<div class="post"><div class="entry color-purple">正在采集...</div></div>';
        	$('#content').prepend(html);

			remains = 0;
			self.clearInterval(timerId);
			setTimeout(start, 1000);
		}
	}

	function clearInfo() {
		$('#content').empty();
	}
});