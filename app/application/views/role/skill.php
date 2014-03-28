    <link href="<?php echo base_url('resources/css/skill.css'); ?>" rel="stylesheet" type="text/css" />
    <div class="left">
        <h3>职业树</h3>
        <div id="tmp">
            <?php foreach($job as $item): ?>
            <div class="job_item<?php if($item['id'] == $role['job']): ?> current_job<?php endif; ?>">
                <span class="id" style="display:none;"><?php echo $item['id']; ?></span>
                <span class="pre_job" style="display:none;"><?php echo $item['pre_job']; ?></span>
                <span class="job_name"><?php echo $item['name']; ?></span>
                <div class="sub_job"></div>
            </div>
            <?php endforeach; ?>
        </div>
        <div id="job">
        </div>
    </div>
    <div class="right">
        <p style="color:#FF0000;font-weight:bold;">转职后，以前职业的技能将不能再学习！</p>
        <div id="job_detail">
        <?php
        foreach($job as $item)
        {
            if($item['id'] == $role['job'])
            {
                echo '<div class="detail" id="job_' . $item['id'] . '_skill">';
            }
            else
            {
                echo '<div class="detail" id="job_' . $item['id'] . '_skill" style="display:none;">';
            }
            echo '<div class="job_comment">';
            echo '<p>名称：' . $itemp['name'] . '</p>';
            echo '<p>职业简介</p>';
            echo '<p>' . $item['comment'] . '</p>';
            if($role['job'] == $item['id'])
            {
                echo '<p>已成为 ' . $item['name'] . '</p>';
            }
            elseif($role['level'] >= $item['level'])
            {
                echo '<p><a href="' . site_url('role/skill/change_job/' . $item['id']) . '">现在就转职成为 ' . $item['name'] . '</a></p>';
            }
            else
            {
                echo '<p>转职条件：等级达到' . $item['level'] . '级</p>';
            }
            echo '</div>';
            echo '<hr />';
            echo '<div class="job_skill">';

            foreach($item['skill'] as $skill)
            {
                echo '<div class="job_skill_detail" id="skill_' . $skill['id'] . '">';
                echo '<p>技能名称：' . $skill['name'] . '</p>';
                echo '<p>技能描述：' . $skill['comment'] . '</p>';
                echo '<p>等级限制：' . $skill['level_limit'] . ' 级</p>';
                echo '<p>技能释放：' . ($skill['is_passive'] == 1 ? '被动' : '主动') . '</p>';
                echo '<p>技能状态：';
                if(in_array($skill['id'], $role['skill']))
                {
                    echo '已学习';
                    if($skill['id'] != $role['main_skill'])
                    {
                        echo ' | <a href="' . site_url('role/skill/set_main_skill/' . $skill['id']) . '">设为主要技能</a>';
                    }
                    else
                    {
                        echo ' | <a href="' . site_url('role/skill/unset_main_skill/' . $skill['id']) . '">取消主要技能</a>';
                    }
                }
                else
                {
                    if($role['level'] >= $skill['level_limit'])
                    {
                        echo '<a href="' . site_url('role/skill/learn/' . $skill['id']) . '">可学习</a>';
                    }
                    else
                    {
                        echo '等级不足';
                    }
                }
                echo '</p>';
                echo '</div>';
            }
            echo '</div>';
            echo '</div>';
        }
        ?>
        </div>
    </div>
    <div class="clear"></div>
    <script src="<?php echo base_url('resources/js/skill.js'); ?>" type="text/javascript"></script>