<div class="info">
<p>角色名：<?php echo $role->role_name; ?></p>
<p>等级：<?php echo $role->role_level; ?></p>
<p>经验：<?php echo number_format($role->role_exp); ?>/<?php echo number_format($role->role_nextexp); ?></p>
<p>种族：<?php echo $role->role_race; ?></p>
<p>职业：<?php echo $role->role_job; ?></p>
<p>生命值：<?php echo number_format($role->role_health); ?>/<?php echo number_format($role->role_health_max); ?></p>
<p>攻击：<?php echo $role->role_atk; ?></p>
<p>防御：<?php echo $role->role_def; ?></p>
<p>魔抗：<?php echo $role->role_mdef; ?></p>
<p>命中：<?php echo $role->role_hit; ?></p>
<p>闪避：<?php echo $role->role_flee; ?></p>
</div>
<div class="clear"></div>