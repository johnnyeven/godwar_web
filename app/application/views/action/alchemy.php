    <link href="<?php echo base_url('resources/css/flick/jquery-ui-1.10.4.custom.min.css'); ?>" rel="stylesheet" type="text/css" />
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
        <div id="content"></div>
        <div id="queue">
            <h3>合成队列</h3>
        <?php
        foreach($queue as $value)
        {
            echo '<div class="queue_item">';
            echo '<span class="queue_name">' . $value['name'] . '</span>';
            echo ' | <span class="queue_starttime">' . date('Y-m-d H:i:s', $value['starttime']) . '</span>';
            echo ' | <span class="queue_endtime">' . date('Y-m-d H:i:s', $value['endtime']) . '</span>';
            if($value['status'] == '1')
            {
                echo ' | <span class="queue_control">已完成，<a href="#" rel="' . $value['product_id'] . '">放入背包</a></span>';
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
    <div class="clear"></div>
    <div id="dialog_message" title="信息">
        <p id="dialog_message_content"></p>
    </div>
    <script src="<?php echo base_url('resources/js/jquery-ui-1.10.4.custom.min.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('resources/js/const.config.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('resources/js/alchemy.js'); ?>" type="text/javascript"></script>