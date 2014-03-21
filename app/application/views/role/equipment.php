	<script src="<?php echo base_url('resources/js/battle.js'); ?>" type="text/javascript"></script>
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
    	<?php foreach($equipments as $equipment): ?>
        <div class="equipment" style="padding:5px 20px;float:left;min-width:120px;"><?php echo $equipment['name']; ?></div>
        <?php endforeach; ?>
        <div style="clear:both;"></div>
    </div>
    <div class="clear"></div>