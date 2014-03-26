    <script src="<?php echo base_url('resources/js/skill.js'); ?>" type="text/javascript"></script>
    <div class="left">
    
    </div>
    <div class="right">
        <div id="job">
            <?php foreach($job as $item): ?>
            <div id="job_<?php echo $item['id']; ?>"><?php echo $item['name']; ?></div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="clear"></div>