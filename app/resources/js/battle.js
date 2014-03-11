$(function() {
	var run = false;
	var timerId = "";
	var remains = 0;
	var currentIndex = 0;
	var currentData;
	
	$("#btnStartBattle").click(function() {
		if(!run) {
			run = true;
			startBattle();
		}
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
		} else {
			clearInfo();
			currentData = data;
			timerId = self.setInterval(showBattleRound, 1000);
		}
	}

	function showBattleRound() {
		if(currentIndex >= currentData.rounds.length) {
			currentIndex = 0;
			self.clearInterval(timerId);
			startBattle();
		} else {
			var data = currentData.rounds[currentIndex];
			var html = '<div class="post"><div class="entry">' + data.round + '. <span class="attacker">' + data.attacker.name + '</span> 对 <span class="defender">' + data.defender.name + '</span>';
			var skill = '';
			if(data.damage.skill != '') {
				skill = ' 使用 <span class="skill">' + data.damage.skill + '</span>';
			}
			html += skill + ' 造成 <span class="damage">' + data.damage.damage + '</span> 点伤害</div></div>';
        	$('#content').prepend(html);
        	currentIndex++;
		}
	}
	
	function checkTimer() {
		if(remains > 1) {
			remains--;
			$("#findTimer").text("(" + remains + ")");
		} else {
			remains = 0;
			startBattle();
			self.clearInterval(timerId);
		}
	}

	function clearInfo() {
		$('#content').empty();
	}
});