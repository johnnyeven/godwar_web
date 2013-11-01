<div class="info">
<p>角色名：<?php echo $role['name']; ?></p>
<p>等级：<?php echo $role['level']; ?></p>
<p>经验：<?php echo number_format($role['exp']); ?>/<?php echo number_format($role['nextexp']); ?></p>
<p>种族：<?php echo $role['race']; ?></p>
<p>职业：<?php echo $role['job']; ?></p>
<p>生命值：<?php echo number_format($role['health']); ?>/<?php echo number_format($role['health_max']); ?></p>
<p>攻击：<?php echo $role['atk']; ?></p>
<p>防御：<?php echo $role['def']; ?></p>
<p>魔抗：<?php echo $role['mdef']; ?></p>
<p>命中：<?php echo $role['hit']; ?></p>
<p>闪避：<?php echo $role['flee']; ?></p>
</div>
<div class="clear"></div>