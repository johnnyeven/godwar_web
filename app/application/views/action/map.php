	<script src="<?php echo base_url('resources/js/battle.js'); ?>" type="text/javascript"></script>
    <div class="left">
    
    </div>
    <div class="right">
        <div id="maps">
        	<h5>地图</h5>
        	<?php foreach($maps as $map): ?>
        	<div style="float:left;width:100px;height:30px;line-height:30px;padding:0 10px;<?php if($role['map_id'] == $map['id']): ?>background:#FC3;<?php endif; ?>"><a href="<?php echo site_url('action/map/info/' . $map['id']); ?>"><?php echo $map['name']; ?></a></div>
        	<?php endforeach; ?>
            <div style="clear:both"></div>
        </div>
        <div id="monsters">
        	<h5><?php echo $current_selected_map['name']; ?>包含的野怪</h5>
        	<?php foreach($monsters as $monster): ?>
            <div style="float:left;width:100px;height:30px;line-height:30px;padding:0 10px;"><?php echo $monster['name']; ?></div>
            <?php endforeach; ?>
            <div style="clear:both"></div>
        </div>
        <div id="control">
        	<p><a href="<?php echo site_url('action/map/move/' . $current_selected_map['id']); ?>">移动到 <?php echo $current_selected_map['name']; ?></a></p>
        </div>
    </div>
    <div class="clear"></div>