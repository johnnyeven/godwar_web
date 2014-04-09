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
		            echo '<ul class="menu" style="display:none;margin-left:10px;">';
		            if($equipment['is_equipped'] == '1')
		            {
		                echo '<li><a href="' . site_url('role/equipment/unequip/' . $equipment['id']) . '"><span class="ui-icon ui-icon-disk"></span>卸下</a></li>';
		            }
		            else
		            {
		                echo '<li><a href="' . site_url('role/equipment/equip/' . $equipment['id']) . '"><span class="ui-icon ui-icon-disk"></span>装备</a></li>';
		            }
		            echo '<li><a href="' . site_url('role/equipment/sell/' . $equipment['id']) . '"><span class="ui-icon ui-icon-disk"></span>出售</a></li>';
		            echo '<li><a href="' . site_url('action/market/sell/' . $equipment['id']) . '"><span class="ui-icon ui-icon-disk"></span>拍卖行</a></li>';
		            if($equipment['is_locked'] == '1')
		            {
		                echo '<li><a href="' . site_url('role/equipment/unlock/' . $equipment['id']) . '"><span class="ui-icon ui-icon-disk"></span>解锁</a></li>';
		            }
		            else
		            {
		                echo '<li><a href="' . site_url('role/equipment/lock/' . $equipment['id']) . '"><span class="ui-icon ui-icon-disk"></span>上锁</a></li>';
		            }
		            echo '<li><a href="' . site_url('role/equipment/destroy/' . $equipment['id']) . '"><span class="ui-icon ui-icon-disk"></span>销毁</a></li>';
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