    <link href="<?php echo base_url('resources/css/gift.css'); ?>" rel="stylesheet" type="text/css" />
    <div class="left">
        <h3><?php echo $race['name']; ?></h3>
        <div id="race_detail">
            <p>生命成长：<?php echo $race['health_inc']; ?></p>
            <p>攻击成长：<?php echo $race['atk_inc']; ?></p>
            <p>防御成长：<?php echo $race['def_inc']; ?></p>
            <p>魔抗成长：<?php echo $race['mdef_inc']; ?></p>
            <p>命中成长：<?php echo $race['hit_inc']; ?></p>
            <p>闪避成长：<?php echo $race['flee_inc']; ?></p>
        </div>
    </div>
    <div class="right">
        <div id="gift_detail">
        <?php
        $gifts = $race['gift'];
        foreach($gifts as $item)
        {
            echo '<div class="gift" id="gift_' . $item['id'] . '">';
            echo '<p>' . $item['name'] . '</p>';
            echo '<p>' . $item['comment'] . '</p>';
            if(in_array($item['id'], $role['gift']))
            {
                echo '<p>已激活</p>';
            }
            else
            {
                if($role['level'] >= $item['level_limit'])
                {
                    echo '<p><a href="' . site_url('role/gift/activate/' . $item['id']) . '">立即激活</a></p>';
                }
                else
                {
                    echo '<p>等级限制：' . $item['level_limit'] . '</p>';
                }
            }
            
            echo '</div>';
        }
        ?>
        </div>
    </div>
    <div class="clear"></div>
    <script src="<?php echo base_url('resources/js/gift.js'); ?>" type="text/javascript"></script>