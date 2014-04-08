<link href="<?php echo base_url('resources/css/tow_column.common.css'); ?>" rel="stylesheet" type="text/css" />
    	<div class="left">
            <div class="row-item with-border-bottom">
                <div class="role-info-item">
                    <span class="role-info-item-label">角色名</span>
                    <span class="role-info-item-value"><?php echo $role['name']; ?></span>
                </div>
                <div class="role-info-item">
                    <span class="role-info-item-label">等级</span>
                    <span class="role-info-item-value"><?php echo $role['level']; ?></span>
                </div>
                <div class="role-info-item">
                    <span class="role-info-item-label">经验</span>
                    <span class="role-info-item-value"><?php echo number_format($role['exp']); ?> / <?php echo number_format($role['nextexp']); ?></span>
                </div>
                <div class="role-info-item">
                    <span class="role-info-item-label">金币</span>
                    <span class="role-info-item-value"><?php echo number_format($role['gold']); ?></span>
                </div>
                <div class="role-info-item">
                    <span class="role-info-item-label">种族</span>
                    <span class="role-info-item-value"><?php echo $role['race_name']; ?></span>
                </div>
                <div class="clear"></div>
            </div>
            <div class="row-item">
                <div class="role-info-item">
                    <span class="role-info-item-label">生命值</span>
                    <span class="role-info-item-value"><?php echo number_format($role['health']); ?> / <?php echo number_format($role['health_max']); ?></span>
                </div>
                <div class="role-info-item">
                    <span class="role-info-item-label">攻击</span>
                    <span class="role-info-item-value"><?php echo $role['atk']; ?></span>
                </div>
                <div class="role-info-item">
                    <span class="role-info-item-label">防御</span>
                    <span class="role-info-item-value"><?php echo $role['def']; ?></span>
                </div>
                <div class="role-info-item">
                    <span class="role-info-item-label">魔抗</span>
                    <span class="role-info-item-value"><?php echo $role['mdef']; ?></span>
                </div>
                <div class="role-info-item">
                    <span class="role-info-item-label">命中</span>
                    <span class="role-info-item-value"><?php echo $role['hit']; ?></span>
                </div>
                <div class="role-info-item">
                    <span class="role-info-item-label">闪避</span>
                    <span class="role-info-item-value"><?php echo $role['flee']; ?></span>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <div class="right">
        	<div class="right_top"></div>
            <div class="right_main">
                <div class="sina_weibo">
            <?php
            if(empty($role_thirdpart['sina_weibo_id']))
            {
                if(empty($user['sina_weibo_id']))
                {
                    echo '<span>还没有绑定新浪微博，<a href="' . $sina_weibo_url . '">现在就去绑定</a></span>';
                }
                else
                {
                    echo '<span>已成功绑定新浪微博，ID: ' . $user['sina_weibo_id'] . '</span>';
                }
            }
            else
            {
                echo '<span>已成功绑定新浪微博，ID: ' . $role_thirdpart['sina_weibo_id'] . '</span>';
            }
            ?>
            </div>
                <div class="tencent_weibo">
                <?php
                if(empty($role_thirdpart['tencent_weibo_id']))
                {
                    if(empty($user['tencent_weibo_id']))
                    {
                        echo '<span>还没有绑定腾讯微博，<a href="' . $tencent_weibo_url . '">现在就去绑定</a></span>';
                    }
                    else
                    {
                        echo '<span>已成功绑定腾讯微博，ID: ' . $user['tencent_weibo_id'] . '</span>';
                    }
                }
                else
                {
                    echo '<span>已成功绑定腾讯微博，ID: ' . $role_thirdpart['tencent_weibo_id'] . '</span>';
                }
                ?>
                </div>
                <div class="renren">
                <?php
                if(empty($role_thirdpart['renren_id']))
                {
                    if(empty($user['renren_id']))
                    {
                        echo '<span>还没有绑定人人网帐号，<a href="' . $renren_url . '">现在就去绑定</a></span>';
                    }
                    else
                    {
                        echo '<span>已成功绑定人人网帐号，ID: ' . $user['renren_name'] . '</span>';
                    }
                }
                else
                {
                    echo '<span>已成功绑定人人网帐号，ID: ' . $role_thirdpart['renren_name'] . '</span>';
                }
                ?>
                </div>
            </div>
            <div class="right_bottom"></div>
        </div>
    	<div class="clear"></div>