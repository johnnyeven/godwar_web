	<script src="<?php echo base_url('resources/js/equipment.js'); ?>" type="text/javascript"></script>
    <div class="left">
        <p>头盔：</p>
        <p>盔甲：</p>
        <p>武器：</p>
        <p>护肩：</p>
        <p>鞋子：</p>
        <p>戒指：</p>
        <p>项链：</p>
    </div>
    <div class="right">
        <div id="content">
        <?php
        foreach($equipments as $equipment)
        {
            echo '<div class="equipment" style="padding:5px 20px;float:left;min-width:200px;">';
            if($equipment['grade'] == 0)
            {
                echo '<span style="color:#000000">' . $equipment['name'] . '</span>';
            }
            elseif($equipment['grade'] == 1)
            {
                echo '<span style="color:#0099FF">' . $equipment['name'] . '</span>';
            }
            elseif($equipment['grade'] == 2)
            {
                echo '<span style="color:#2BD52B">' . $equipment['name'] . '</span>';
            }
            elseif($equipment['grade'] == 3)
            {
                echo '<span style="color:#CC00FF">' . $equipment['name'] . '</span>';
            }
            elseif($equipment['grade'] == 4)
            {
                echo '<span style="color:#FF9900">' . $equipment['name'] . '</span>';
            }
            echo '<span class="control" style="display:none;margin-left:10px;">';
            if($equipment['is_equipped'] == '1')
            {
                echo '<a href="' . site_url('role/equipment/unequip/' . $equipment['id']) . '">卸下</a>';
            }
            else
            {
                echo '<a href="' . site_url('role/equipment/equip/' . $equipment['id']) . '">装备</a>';
            }
            echo ' | <a href="' . site_url('role/equipment/sell/' . $equipment['id']) . '">出售</a>';
            echo ' | <a href="">锁定</a>';
            echo '</span>';
            echo '</div>';
        }
        ?>
        <div style="clear:both;"></div>
        </div>
    </div>
    <div class="clear"></div>