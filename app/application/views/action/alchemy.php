<link href="<?php echo base_url('resources/css/tow_column.common.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('resources/css/alchemy.css'); ?>" rel="stylesheet" type="text/css" />
    	<div class="left">
	        <h3>已学会的配方</h3>
	        <div id="blueprint_list">
	        <?php foreach ($result as $value)
	        {
	            echo '<p rel="' . $value['id'] . '">' . $value['name'] . '</p>';
	        }
	        ?>
	        </div>
        </div>
        <div class="right">
        	<div class="right_top"></div>
            <div class="right_main">
	            <div id="content"></div>
		        <div id="queue" class="row-item">
		            <h3>合成队列</h3>
		        <?php
		        foreach($queue as $value)
		        {
		            echo '<div class="queue_item" id="queue_item_' . $value['id'] . '">';
		            echo '<span class="queue_name">' . $value['name'] . '</span>';
		            echo ' | <span class="queue_starttime">' . date('Y-m-d H:i:s', $value['starttime']) . '</span>';
		            echo ' | <span class="queue_endtime">' . date('Y-m-d H:i:s', $value['endtime']) . '</span>';
		            if($value['status'] == '1')
		            {
		                echo ' | <span class="queue_control">已完成<a href="#" class="queue_complete" rel="' . $value['id'] . '"><button class="btn btn-mini btn-success">放入背包</button></a></span>';
		            }
		            echo '</div>';
		        }
		        ?>
		        </div>
		        <div id="item">
		        <?php
		        foreach($items as $value)
		        {
		            echo '<div class="item_detail">';
		            echo '<span class="item_id">' . $value['id'] . '</span>';
		            echo '<span class="item_count">' . $value['count'] . '</span>';
		            echo '</div>';
		        }
		        ?>
		        </div>
            </div>
            <div class="right_bottom"></div>
        </div>
    	<div class="clear"></div>
	    <div id="dialog_message" title="信息">
	        <p id="dialog_message_content"></p>
	    </div>
	    <div id="dialog_alert" class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;display:none;position:absolute;right:10px;top:10px;z-index:10000;">
	        <p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
	        <strong class="dialog_alert_content"></strong></p>
	    </div>
<script src="<?php echo base_url('resources/js/const.config.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('resources/js/alchemy.js'); ?>" type="text/javascript"></script>