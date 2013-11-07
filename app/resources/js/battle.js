$(function() {
	var run = false;
	var timerId = "";
	var remains = 0;
	
	$("#btnStartBattle").click(function() {
		if(!run) {
			run = true;
		}
		startBattle();
	});
	
	function startBattle() {
		if(run) {
			$.post("/godwar_web/app/action/battle/request_battle", {}, showBattle);
		}
	}
	
	function showBattle(data) {
		if(data.err) {
			remains = data.next_battletime - data.timestamp;
			var html = '<div class="post"><div class="entry">战斗刚结束，正在休息恢复中，并搜寻敌人...<em id="findTimer">(' + remains + ')</em></div></div>';
        	$('#content').prepend(html);
        	timerId = self.setInterval(checkTimer, 1000);
		}
	}
	
	function checkTimer() {
		if(remains > 1) {
			remains--;
		} else {
			remains = 0;
			startBattle();
			self.clearInterval(timerId);
		}
		$("#findTimer").text("(" + remains + ")");
	}
});