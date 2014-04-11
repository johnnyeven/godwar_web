$(function() {
	var run = false;
	var timerId = "";
	var remains = 0;
	var currentIndex = 0;
	var currentData;

	var health = parseInt($("#role_health").text());
	var healthMax = parseInt($("#role_health_max").text());
	var healthPercent = parseInt(health / healthMax * 100);
	if(healthPercent >= 60) {
		$("#role_health_bar").addClass("progress-success");
	} else if(healthPercent >= 30) {
		$("#role_health_bar").addClass("progress-warning");
	} else {
		$("#role_health_bar").addClass("progress-danger");
	}
	$("#role_health_bar > div.bar").css("width", healthPercent + "%");
	$("#health_percent").text(healthPercent + "%");
	
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
			$.post("battle/request_battle", {}, showBattle);
		}
	}
	
	function showBattle(data) {
		if(data.err) {
			var err = parseInt(data.err);
			var html;

			switch(err) {
				case 1:
					remains = data.next_battletime - data.timestamp;
					html = '<div class="post"><div class="entry color-purple">战斗刚结束，正在休息恢复中，并搜寻敌人...<em id="findTimer">(' + remains + ')</em></div></div>';
		        	$('#content').prepend(html);
		        	timerId = self.setInterval(checkTimer, 1000);
					break;
				case 1042:
					clearInfo();
					html = '<div class="post"><div class="entry color-red">您已阵亡，请前往圣灵祭坛复活</div></div>';
					$('#content').prepend(html);
					break;
			}
		} else {
			clearInfo();
			currentData = data;
			$("#monster_name").text(currentData.monster.name);
			$("#monster_level").text(currentData.monster.level);
			$("#monster_health").text(currentData.monster.health);
			$("#monster_health_max").text(currentData.monster.health_max);
			$("#monster_atk").text(currentData.monster.atk);
			$("#monster_def").text(currentData.monster.def);
			$("#monster_mdef").text(currentData.monster.mdef);
			$("#monster_hit").text(currentData.monster.hit);
			$("#monster_flee").text(currentData.monster.flee);
			
			health = parseInt($("#monster_health").text());
			healthMax = parseInt($("#monster_health_max").text());
			healthPercent = parseInt(health / healthMax * 100);
			$("#monster_health_bar").removeClass("progress-success").removeClass("progress-warning").removeClass("progress-danger");
			if(healthPercent >= 60) {
				$("#monster_health_bar").addClass("progress-success");
			} else if(healthPercent >= 30) {
				$("#monster_health_bar").addClass("progress-warning");
			} else {
				$("#monster_health_bar").addClass("progress-danger");
			}
			$("#monster_health_bar > div.bar").css("width", healthPercent + "%");
			$("#monster_health_percent").text(healthPercent + "%");
			$("#monster_health_bar").addClass("active");
			$("#monster_info").show();
			timerId = self.setInterval(showBattleRound, 1500);
		}
	}

	function showBattleRound() {
		var data;
		var html;
		var health, healthMax, healthPercent;
		if(currentIndex >= currentData.rounds.length) {
			self.clearInterval(timerId);

			data = currentData.rounds[currentIndex-1];
			html = '<div class="post"><div class="entry">';
			if(currentData.result == 1) {
				html += '战斗胜利，获得经验 <span class="exp">' + currentData.settle.exp + '</span>，金币 <span class="gold">' + currentData.settle.gold + '</span>';
				$("#role_gold").text(currentData.final.gold);
				$("#role_exp").text(currentData.final.exp);
				$("#role_exp_next").text(currentData.final.nextexp);
				$("#role_level").text(currentData.final.level);
				$("#role_health").text(currentData.final.health);
				$("#role_atk").text(currentData.final.atk);
				$("#role_def").text(currentData.final.def);
				$("#role_mdef").text(currentData.final.mdef);
				$("#role_hit").text(currentData.final.hit);
				$("#role_flee").text(currentData.final.flee);

				health = parseInt($("#role_health").text());
				healthMax = parseInt($("#role_health_max").text());
				healthPercent = parseInt(health / healthMax * 100);
				$("#role_health_bar").removeClass("progress-success").removeClass("progress-warning").removeClass("progress-danger");
				if(healthPercent >= 60) {
					$("#role_health_bar").addClass("progress-success");
				} else if(healthPercent >= 30) {
					$("#role_health_bar").addClass("progress-warning");
				} else {
					$("#role_health_bar").addClass("progress-danger");
				}
				$("#role_health_bar > div.bar").css("width", healthPercent + "%");
				$("#health_percent").text(healthPercent + "%");

				$("#monster_health_bar").removeClass("progress-success");
				$("#monster_health_bar").removeClass("progress-warning");
				$("#monster_health_bar").removeClass("progress-danger");
				$("#monster_health_bar").removeClass("active");
				$("#monster_health_bar").removeClass("progress-success").removeClass("progress-warning").removeClass("progress-danger");
				$("#monster_info").hide();

				if(currentData.settle.drop.length > 0)
				{
					html += '，获得道具';
					for(var i = 0; i<currentData.settle.drop.length; i++) {
						if(currentData.settle.drop[i].grade == 1) {
							html += ' <span class="equipment color-blue">';
						} else if(currentData.settle.drop[i].grade == 2) {
							html += ' <span class="equipment color-green">';
						} else if(currentData.settle.drop[i].grade == 3) {
							html += ' <span class="equipment color-purple">';
						} else if(currentData.settle.drop[i].grade == 4) {
							html += ' <span class="equipment color-orange">';
						} else {
							html += ' <span class="equipment">';
						}
						html += currentData.settle.drop[i].name + '</span>';
					}
				}
			} else {
				html += '战斗失败';
			}
			html += '</div></div>';
        	$('#content').prepend(html);

			currentIndex = 0;
			startBattle();
		} else {
			data = currentData.rounds[currentIndex];

			var role, monster;
			if(data.attacker.account_id) {
				role = data.attacker;
				monster = data.defender;
			} else {
				role = data.defender;
				monster = data.attacker;
			}
			$("#role_level").text(role.level);
			$("#role_health").text(role.health);
			$("#role_atk").text(role.atk);
			$("#role_def").text(role.def);
			$("#role_mdef").text(role.mdef);
			$("#role_hit").text(role.hit);
			$("#role_flee").text(role.flee);

			health = parseInt($("#role_health").text());
			healthMax = parseInt($("#role_health_max").text());
			healthPercent = parseInt(health / healthMax * 100);
			$("#role_health_bar").removeClass("progress-success").removeClass("progress-warning").removeClass("progress-danger");
			if(healthPercent >= 60) {
				$("#role_health_bar").addClass("progress-success");
			} else if(healthPercent >= 30) {
				$("#role_health_bar").addClass("progress-warning");
			} else {
				$("#role_health_bar").addClass("progress-danger");
			}
			$("#role_health_bar > div.bar").css("width", healthPercent + "%");
			$("#health_percent").text(healthPercent + "%");

			$("#monster_health").text(monster.health);
			$("#monster_health_max").text(monster.health_max);
			$("#monster_atk").text(monster.atk);
			$("#monster_def").text(monster.def);
			$("#monster_mdef").text(monster.mdef);
			$("#monster_hit").text(monster.hit);
			$("#monster_flee").text(monster.flee);

			health = parseInt($("#monster_health").text());
			healthMax = parseInt($("#monster_health_max").text());
			healthPercent = parseInt(health / healthMax * 100);
			$("#monster_health_bar").removeClass("progress-success").removeClass("progress-warning").removeClass("progress-danger");
			if(healthPercent >= 60) {
				$("#monster_health_bar").addClass("progress-success");
			} else if(healthPercent >= 30) {
				$("#monster_health_bar").addClass("progress-warning");
			} else {
				$("#monster_health_bar").addClass("progress-danger");
			}
			$("#monster_health_bar > div.bar").css("width", healthPercent + "%");
			$("#monster_health_percent").text(healthPercent + "%");

			html = '<div class="post"><div class="entry">' + data.round + '. <span class="attacker">' + data.attacker.name + '</span> 对 ';
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