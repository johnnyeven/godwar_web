    <script src="<?php echo base_url('resources/js/skill.js'); ?>" type="text/javascript"></script>
    <div class="left">
    
    </div>
    <div class="right">
        <div id="tmp">
            <?php foreach($job as $item): ?>
            <div class="job_item">
                <span class="id" style="display:none;"><?php echo $item['id']; ?></span>
                <span class="pre_job" style="display:none;"><?php echo $item['pre_job']; ?></span>
                <h3><?php echo $item['name']; ?></h3>
                <div class="sub_job"></div>
            </div>
            <?php endforeach; ?>
        </div>
        <div id="job">
        </div>
    </div>
    <div class="clear"></div>