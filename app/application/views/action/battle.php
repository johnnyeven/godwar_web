<link href="<?php echo base_url('resources/css/tow_column.common.css'); ?>" rel="stylesheet" type="text/css" />
    	<div class="left">
            <div class="role">
                <div class="avatar-container row-item">
                    <div id="avatar" class="avatar"><img src="<?php echo base_url('resources/images/avatar/spirit_maiya_big.png'); ?>" /></div>
                    <div id="role_health_bar" class="progress progress-mini active progress-striped">
                        <div class="bar" style="width:100%"></div>
                    </div>
                    <div id="health_percent" class="percent">100%</div>
                </div>
                <div class="with-border-bottom row-item" id="role_info">
                    <div class="role-info-item">
                        <span class="role-info-item-label">角色名</span>
                        <span class="role-info-item-value"><?php echo $role['name']; ?></span>
                    </div>
                    <div class="role-info-item">
                        <span class="role-info-item-label">等级</span>
                        <span class="role-info-item-value" id="role_level"><?php echo $role['level']; ?></span>
                    </div>
                    <div class="role-info-item">
                        <span class="role-info-item-label">经验</span>
                        <span class="role-info-item-value"><span id="role_exp"><?php echo $role['exp']; ?></span> / <span id="role_exp_next"><?php echo $role['nextexp']; ?></span></span>
                    </div>
                    <div class="role-info-item">
                        <span class="role-info-item-label">金币</span>
                        <span class="role-info-item-value" id="role_gold"><?php echo $role['gold']; ?></span>
                    </div>
                    <div class="role-info-item">
                        <span class="role-info-item-label">生命值</span>
                        <span class="role-info-item-value"><span id="role_health"><?php echo $role['health']; ?></span> / <span id="role_health_max"><?php echo $role['health_max']; ?></span></span>
                    </div>
                    <div class="role-info-item">
                        <span class="role-info-item-label">攻击</span>
                        <span class="role-info-item-value" id="role_atk"><?php echo $role['atk']; ?></span>
                    </div>
                    <div class="role-info-item">
                        <span class="role-info-item-label">防御</span>
                        <span class="role-info-item-value" id="role_def"><?php echo $role['def']; ?></span>
                    </div>
                    <div class="role-info-item">
                        <span class="role-info-item-label">魔抗</span>
                        <span class="role-info-item-value" id="role_mdef"><?php echo $role['mdef']; ?></span>
                    </div>
                    <div class="role-info-item">
                        <span class="role-info-item-label">命中</span>
                        <span class="role-info-item-value" id="role_hit"><?php echo $role['hit']; ?></span>
                    </div>
                    <div class="role-info-item">
                        <span class="role-info-item-label">闪避</span>
                        <span class="role-info-item-value" id="role_flee"><?php echo $role['flee']; ?></span>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="vs-icon row-item with-border-bottom"
            <div class="monster">
                <div class="avatar row-item">

                </div>
            </div>
        </div>
        <div class="right">
        	<div class="right_top"></div>
            <div class="right_main">
	            <div class="row-item controlPanel">
                    <button class="btn" id="btnStartBattle">战斗</button>
                    <button class="btn" id="btnStopBattle">停止</button>
		    	</div>
		        <div class="row-item" id="content">
		        
		        </div>
            </div>
            <div class="right_bottom"></div>
        </div>
    	<div class="clear"></div>
<script src="<?php echo base_url('resources/js/battle.js'); ?>" type="text/javascript"></script>