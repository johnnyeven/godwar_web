
    <div class="left">
		<p>角色名：<?php echo $role['name']; ?></p>
		<p>等级：<?php echo $role['level']; ?></p>
		<p>经验：<?php echo number_format($role['exp']); ?>/<?php echo number_format($role['nextexp']); ?></p>
		<p>金币：<?php echo number_format($role['gold']); ?></p>
		<p>种族：<?php echo $role['race_name']; ?></p>
		<p>职业：<?php echo $role['job_name']; ?></p>
		<p>生命值：<?php echo number_format($role['health']); ?>/<?php echo number_format($role['health_max']); ?></p>
		<p>攻击：<?php echo $role['atk']; ?></p>
		<p>防御：<?php echo $role['def']; ?></p>
		<p>魔抗：<?php echo $role['mdef']; ?></p>
		<p>命中：<?php echo $role['hit']; ?></p>
		<p>闪避：<?php echo $role['flee']; ?></p>
	</div>
    <div class="right">
    	<div class="sina_weibo">
	    <?php
		if(empty($role['sina_weibo_id']))
		{
			if(empty($user['sina_weibo_id']))
			{
				echo '<p>还没有绑定新浪微博，<a href="' . $sina_weibo_url . '">现在就去绑定</a></p>';
			}
			else
			{
				echo '<p>已成功绑定新浪微博，ID: ' . $user['sina_weibo_id'] . '</p>';
			}
		}
		else
		{
			echo '<p>已成功绑定新浪微博，ID: ' . $role['sina_weibo_id'] . '</p>';
		}
	    ?>
		</div>
    	<div class="tencent_weibo">
	    <?php
		if(empty($role['tencent_weibo_id']))
		{
			if(empty($user['tencent_weibo_id']))
			{
				echo '<p>还没有绑定腾讯微博，<a href="' . $tencent_weibo_url . '">现在就去绑定</a></p>';
			}
			else
			{
				echo '<p>已成功绑定腾讯微博，ID: ' . $user['tencent_weibo_id'] . '</p>';
			}
		}
		else
		{
			echo '<p>已成功绑定腾讯微博，ID: ' . $role['tencent_weibo_id'] . '</p>';
		}
	    ?>
		</div>
    </div>
	<div class="clear"></div>