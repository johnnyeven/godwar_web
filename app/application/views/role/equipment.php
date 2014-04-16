<link href="<?php echo base_url('resources/css/tow_column.common.css'); ?>" rel="stylesheet" type="text/css" />
    	<div class="left">
            <div class="row-item with-border-bottom">
	        <?php
	        foreach($equipment_title as $key => $title)
	        {
	            echo '<div class="equipment-info-item"><span class="equipment-info-item-label">' . $title . '</span>';
	            if($equipped[$key]['grade'] == 0)
	            {
	                echo '<span class="equipment-info-item-value">' . $equipped[$key]['name'] . '</span>';
	            }
	            elseif($equipped[$key]['grade'] == 1)
	            {
	                echo '<span class="equipment-info-item-value color-blue">' . $equipped[$key]['name'] . '</span>';
	            }
	            elseif($equipped[$key]['grade'] == 2)
	            {
	                echo '<span class="equipment-info-item-value color-green">' . $equipped[$key]['name'] . '</span>';
	            }
	            elseif($equipped[$key]['grade'] == 3)
	            {
	                echo '<span class="equipment-info-item-value color-purple">' . $equipped[$key]['name'] . '</span>';
	            }
	            elseif($equipped[$key]['grade'] == 4)
	            {
	                echo '<span class="equipment-info-item-value color-orange">' . $equipped[$key]['name'] . '</span>';
	            }
	            echo '</div>';
	        }
	        ?>
        	</div>
        </div>
        <div class="right">
        	<div class="right_top"></div>
            <div class="right_main">
            	<div class="row-item">
            		<a href="<?php echo site_url('role/equipment/sell_all'); ?>"><button class="btn btn-warning">出售所有未上锁的装备</button></a>
            	</div>
		        <div id="content" class="row-item">
		        <?php
		        foreach($equipments as $equipment)
		        {
		            echo '<div class="equipment-item">';
		            if($equipment['grade'] == 0)
		            {
		                echo '<span>' . $equipment['name'] . '</span>';
		            }
		            elseif($equipment['grade'] == 1)
		            {
		                echo '<span class="color-blue">' . $equipment['name'] . '</span>';
		            }
		            elseif($equipment['grade'] == 2)
		            {
		                echo '<span class="color-green">' . $equipment['name'] . '</span>';
		            }
		            elseif($equipment['grade'] == 3)
		            {
		                echo '<span class="color-purple">' . $equipment['name'] . '</span>';
		            }
		            elseif($equipment['grade'] == 4)
		            {
		                echo '<span class="color-orange">' . $equipment['name'] . '</span>';
		            }

		            echo '<div class="equipment-item-detail">';
		            if($equipment['grade'] == 0)
		            {
		                echo '<p class="font-size-16 bold">' . $equipment['name'];
		            }
		            elseif($equipment['grade'] == 1)
		            {
		                echo '<p class="color-blue font-size-16 bold">' . $equipment['name'];
		            }
		            elseif($equipment['grade'] == 2)
		            {
		                echo '<p class="color-green font-size-16 bold">' . $equipment['name'];
		            }
		            elseif($equipment['grade'] == 3)
		            {
		                echo '<p class="color-purple font-size-16 bold">' . $equipment['name'];
		            }
		            elseif($equipment['grade'] == 4)
		            {
		                echo '<p class="color-orange font-size-16 bold">' . $equipment['name'];
		            }
		            if($equipment['upgrade_level'] > 0) echo ' (+' . $equipment['upgrade_level'] . ')</p>';
		            echo '<p>' . $equipment_title[intval($equipment['position'])] . '</p>';
		            echo '<p>' . $equipment['level'] . '级</p>';
		            echo '<p>最高强化等级：' . $equipment['upgrade_level_max'] . '</p>';
		            echo '<hr>';
		            if($equipment['atk_base'] > 0) echo '<p>攻击：' .  $equipment['atk_base'] . '</p>';
		            if($equipment['def_base'] > 0) echo '<p>防御：' .  $equipment['def_base'] . '</p>';
		            if($equipment['mdef_base'] > 0) echo '<p>魔抗：' .  $equipment['mdef_base'] . '</p>';
		            if($equipment['health_max_base'] > 0) echo '<p>生命：' .  $equipment['health_max_base'] . '</p>';
		            if($equipment['hit_base'] > 0) echo '<p>命中：' .  $equipment['hit_base'] . '</p>';
		            if($equipment['flee_base'] > 0) echo '<p>闪避：' .  $equipment['flee_base'] . '</p>';
		            echo '<hr>';
		            $magics = json_decode($equipment['magic_words'], TRUE);
		            foreach($magics as $magic)
		            {
		            	if($magic['property']['atk'] > 0) echo '<p>攻击加成：' . ($magic['property']['atk_unit'] == 1 ? $magic['property']['atk'] : ($magic['property']['atk'] * 100) . '%') . '</p>';
		            	if($magic['property']['def'] > 0) echo '<p>防御加成：' . ($magic['property']['def_unit'] == 1 ? $magic['property']['def'] : ($magic['property']['def'] * 100) . '%') . '</p>';
		            	if($magic['property']['mdef'] > 0) echo '<p>魔抗加成：' . ($magic['property']['mdef_unit'] == 1 ? $magic['property']['mdef'] : ($magic['property']['mdef'] * 100) . '%') . '</p>';
		            	if($magic['property']['health_max'] > 0) echo '<p>生命加成：' . ($magic['property']['health_max_unit'] == 1 ? $magic['property']['health_max'] : ($magic['property']['health_max'] * 100) . '%') . '</p>';
		            	if($magic['property']['hit'] > 0) echo '<p>命中加成：' . ($magic['property']['hit_unit'] == 1 ? $magic['property']['hit'] : ($magic['property']['hit'] * 100) . '%') . '</p>';
		            	if($magic['property']['flee'] > 0) echo '<p>闪避加成：' . ($magic['property']['flee_unit'] == 1 ? $magic['property']['flee'] : ($magic['property']['flee'] * 100) . '%') . '</p>';
		            }
		            echo '</div>';

		            echo '<ul class="menu" style="display:none;margin-left:20px;">';
		            if($equipment['is_equipped'] == '1')
		            {
		                echo '<li><a href="' . site_url('role/equipment/unequip/' . $equipment['id']) . '"><span class="ui-icon ui-icon-arrowthick-1-s"></span>卸下</a></li>';
		            }
		            else
		            {
		                echo '<li><a href="' . site_url('role/equipment/equip/' . $equipment['id']) . '"><span class="ui-icon ui-icon-arrowthick-1-n"></span>装备</a></li>';
		            }
		            echo '<li><a href="' . site_url('role/equipment/sell/' . $equipment['id']) . '"><span class="ui-icon ui-icon-transferthick-e-w"></span>出售</a></li>';
		            echo '<li><a href="' . site_url('action/market/sell/' . $equipment['id']) . '"><span class="ui-icon ui-icon-person"></span>拍卖行</a></li>';
		            if($equipment['is_locked'] == '1')
		            {
		                echo '<li><a href="' . site_url('role/equipment/unlock/' . $equipment['id']) . '"><span class="ui-icon ui-icon-unlocked"></span>解锁</a></li>';
		            }
		            else
		            {
		                echo '<li><a href="' . site_url('role/equipment/lock/' . $equipment['id']) . '"><span class="ui-icon ui-icon-locked"></span>上锁</a></li>';
		            }
		            echo '<li><a href="' . site_url('role/equipment/destroy/' . $equipment['id']) . '"><span class="ui-icon ui-icon-trash"></span>销毁</a></li>';
		            echo '</ul>';
		            echo '</div>';
		        }
		        ?>
		        <div style="clear:both;"></div>
		        </div>
            </div>
            <div class="right_bottom"></div>
        </div>
    	<div class="clear"></div>
<script src="<?php echo base_url('resources/js/equipment.js'); ?>" type="text/javascript"></script>