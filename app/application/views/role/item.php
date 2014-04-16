<link href="<?php echo base_url('resources/css/tow_column.common.css'); ?>" rel="stylesheet" type="text/css" />
    	<div class="left">
        
        </div>
        <div class="right">
        	<div class="right_top"></div>
            <div class="right_main">
            	<div class="row-item">
            		<a href="<?php echo site_url('role/item/sell_all'); ?>"><button class="btn btn-warning">出售所有未上锁的物品</button></a>
            	</div>
		        <div id="content" class="row-item">
		        <?php
		        foreach($result as $row)
		        {
		            echo '<div class="equipment-item">';
		            echo '<span class="id" style="display:none;">' . $row['id'] . '</span>';
		            echo '<span class="type" style="display:none;">' . $row['type'] . '</span>';
		            if($row['type'] == '4')
		            {
		                echo '<span class="name color-red">';
		            }
		            elseif($row['type'] == '2' || $row['type'] == '3')
		            {
		                echo '<span class="name color-purple">';
		            }
		            else
		            {
		                echo '<span class="name">';
		            }
		            echo $row['name'] . '</span>';
		            echo ' (<span class="count">' . $row['count'] . '</span>)';

		            echo '<div class="equipment-item-detail">';
		            echo '<p class="font-size-16 bold">' . $row['name'] . '</p>';
		            echo '<p>' . $row['comment'] . '</p>';
		            echo '</div>';

		            echo '<ul class="menu" style="display:none;margin-left:20px;">';
		            if($row['type'] == '4')
		            {
		                echo '<li><a class="learn_blueprint" href="#"><span class="ui-icon ui-icon-lightbulb"></span>学习</a></li>';
		            }
		            elseif($row['type'] == '2' || $row['type'] == '3')
		            {
		                echo '<li><a class="use" href="#"><span class="ui-icon ui-icon-power"></span>使用</a></li>';
		            }

		            echo '<li><a class="sell" href="#"><span class="ui-icon ui-icon-transferthick-e-w"></span>出售</a></li>';
		            // echo '<li><a href="' . site_url('action/market/sell/' . $row['id']) . '">拍卖行</a></li>';
	                echo '<li';
	                if($row['is_locked'] != '1') echo ' class="hidden"';
	                echo '><a class="unlock" href="#"><span class="ui-icon ui-icon-unlocked"></span>解锁</a></li>';

	                echo '<li';
	                if($row['is_locked'] == '1') echo ' class="hidden"';
	                echo '><a class="lock" href="#"><span class="ui-icon ui-icon-locked"></span>上锁</a></li>';

		            echo '<li><a class="destroy" href="' . site_url('role/item/destroy/' . $row['id']) . '"><span class="ui-icon ui-icon-trash"></span>销毁</a></li>';
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

	    <div id="dialog_form" title="请输入要出售物品的数量">
	        <p>名称：<span class="dialog-item-name" id="dialog_item_name"></span></p>
	        <p>数量：<input type="text" id="dialog_item_count" name="dialog_item_count" value="" /></p>
	    </div>
	    <div id="dialog_message" title="信息">
	        <p id="dialog_message_content"></p>
	    </div>
	    <div id="dialog_alert" class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;display:none;position:absolute;right:10px;top:10px;z-index:10000;">
	        <p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
	        <strong class="dialog_alert_content"></strong></p>
	    </div>
	    
<script src="<?php echo base_url('resources/js/const.config.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('resources/js/item.js'); ?>" type="text/javascript"></script>