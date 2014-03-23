<div class="info">
<p>角色名：<?php echo $role->role['name']; ?></p>
<p>等级：<?php echo $role->role['level']; ?></p>
<p>经验：<?php echo number_format($role->role['exp']); ?>/<?php echo number_format($role->role['nextexp']); ?></p>
<p>种族：<?php echo $role->role['race_name']; ?></p>
<p>职业：<?php echo $role->role['job_name']; ?></p>
<p>生命值：<?php echo number_format($role->role['health']); ?>/<?php echo number_format($role->role['health_max']); ?></p>
<p>攻击：<?php echo $role->role['atk']; ?></p>
<p>防御：<?php echo $role->role['def']; ?></p>
<p>魔抗：<?php echo $role->role['mdef']; ?></p>
<p>命中：<?php echo $role->role['hit']; ?></p>
<p>闪避：<?php echo $role->role['flee']; ?></p>
</div>
<div class="clear"></div>