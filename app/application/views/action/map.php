<link href="<?php echo base_url('resources/css/single.common.css'); ?>" rel="stylesheet" type="text/css" />
        <div class="main">
			<div id="maps" class="row-item">
	        	<h5>地图</h5>
	        	<?php
	        	foreach($maps as $map)
	        	{
	        		if($role['map_id'] == $map['id'])
	        		{
	        			echo '<div class="map-item current-map">' . $map['name'] . '<span class="color-green">[当前]</span></div>';
	        		}
	        		else
	        		{
	        			echo '<div class="map-item"><a href="' . site_url('action/map/info/' . $map['id']) . '">' . $map['name'] . '</a></div>';
	        		}
	        	}
	        	?>
	            <div style="clear:both"></div>
	        </div>
	        <?php if(!empty($current_selected_map['interface'])): ?>
	        <div id="interfaces" class="row-item">
	        	<h5><?php echo $current_selected_map['name']; ?> 提供的功能</h5>
	        	<?php foreach($current_selected_map['interface'] as $interface): ?>
	            <div style="float:left;width:100px;height:30px;line-height:30px;padding:0 10px;"><a href="<?php echo site_url($interface['script']); ?>"><?php echo $interface['name']; ?></a></div>
	            <?php endforeach; ?>
	            <div style="clear:both"></div>
	        </div>
	    	<?php endif; ?>
	        <div id="monsters" class="row-item">
	        	<h5><?php echo $current_selected_map['name']; ?> 包含的野怪</h5>
	        	<?php foreach($monsters as $monster): ?>
	            <div style="float:left;width:100px;height:30px;line-height:30px;padding:0 10px;"><?php echo $monster['name']; ?>(<?php echo $monster['level']; ?>)</div>
	            <?php endforeach; ?>
	            <div style="clear:both"></div>
	        </div>
	        <div id="control" class="row-item">
	        	<a href="<?php echo site_url('action/map/move/' . $current_selected_map['id']); ?>"><button class="btn btn-success">移动到 <?php echo $current_selected_map['name']; ?></button></a>
	        </div>
        </div>