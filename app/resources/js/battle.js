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

	$("#btnStopBattle").click(function() {
		if(run) {
			run = false;
		}
		if(timerId) {
			self.clearInterval(timerId);
		}
	});
	
	function startBattle() {
		if(run) {
			$.post("/godwar_web/app/action/battle/request_battle", {}, showBattle);
		}
	}
	
	function showBattle(data) {
		if(data.err) {
			var err = parseInt(data.err);
			var html;

			switch(err) {
				case 1:
					remains = data.next_battletime - data.timestamp;
					html = '<div class="post"><div class="entry">战斗刚结束，正在休息恢复中，并搜寻敌人...<em id="findTimer">(' + remains + ')</em></div></div>';
		        	$('#content').prepend(html);
		        	timerId = self.setInterval(checkTimer, 1000);
					break;
				case 1042:
					clearInfo();
					html = '<div class="post"><div class="entry">您已阵亡，请前往圣灵祭坛复活</div></div>';
					$('#content').prepend(html);
					break;
			}
		} else {
			clearInfo();
			currentData = data;
			timerId = self.setInterval(showBattleRound, 1500);
		}
	}

	function showBattleRound() {
		if(currentIndex >= currentData.rounds.length) {
			currentIndex = 0;
			self.clearInterval(timerId);
			startBattle();
		} else {
			var data = currentData.rounds[currentIndex];
			var html = '<div class="post"><div class="entry">' + data.round + '. <span class="attacker">' + data.attacker.name + '</span> 对 ';

			if(data.damage[0].target == data.attacker.name) {
				html += '<span class="defender_self">自己</span>';
			} else {
				html += '<span class="defender">' + data.damage[0].target + '</span>';
			}
			
			var skill = '';
			if(data.damage[0].skill != '') {
				skill = ' 使用 <span class="skill">' + data.damage[0].skill + '</span>';
			}
			if(data.damage[0].damage) {
				html += skill + ' 造成 <span class="damage">' + data.damage[0].damage + '</span> 点伤害';
			} else if(data.damage[0].atk_offset) {
				html += skill + ' 造成攻击变化 <span class="damage">' + data.damage[0].atk_offset + '</span> 点';
			} else if(data.damage[0].def_offset) {
				html += skill + ' 造成防御变化 <span class="damage">' + data.damage[0].def_offset + '</span> 点';
			} else if(data.damage[0].mdef_offset) {
				html += skill + ' 造成魔抗变化 <span class="damage">' + data.damage[0].mdef_offset + '</span> 点';
			} else if(data.damage[0].crit_offset) {
				html += skill + ' 造成爆击变化 <span class="damage">' + data.damage[0].crit_offset + '</span> 点';
			} else if(data.damage[0].health_offset) {
				html += skill + ' 造成生命变化 <span class="damage">' + data.damage[0].health_offset + '</span> 点';
			} else {
				html += skill;
			}
        	
			for(var i = 1; i<data.damage.length; i++) {
				html += '，由于 <span class="status">' + data.damage[i].skill + '</span> 对 <span class="defender">' + data.damage[i].target + '</span>';

				if(data.damage[i].damage) {
					html += ' 造成 <span class="damage">' + data.damage[i].damage + '</span> 点伤害';
				} else if(data.damage[i].atk_offset) {
					html += ' 造成攻击变化 <span class="damage">' + data.damage[i].atk_offset + '</span> 点';
				} else if(data.damage[i].def_offset) {
					html += ' 造成防御变化 <span class="damage">' + data.damage[i].def_offset + '</span> 点';
				} else if(data.damage[i].mdef_offset) {
					html += ' 造成魔抗变化 <span class="damage">' + data.damage[i].mdef_offset + '</span> 点';
				} else if(data.damage[i].crit_offset) {
					html += ' 造成爆击变化 <span class="damage">' + data.damage[i].crit_offset + '</span> 点';
				} else if(data.damage[i].health_offset) {
					html += ' 造成生命变化 <span class="damage">' + data.damage[i].health_offset + '</span> 点';
				}
			}
			html += '</div></div>';

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