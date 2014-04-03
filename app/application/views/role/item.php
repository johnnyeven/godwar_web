	<link href="<?php echo base_url('resources/css/flick/jquery-ui-1.10.4.custom.min.css'); ?>" rel="stylesheet" type="text/css" />
    <div class="left">

    </div>
    <div class="right">
        <p><a href="<?php echo site_url('role/item/sell_all'); ?>">出售所有未上锁的物品</a></p>
        <div id="content">
        <?php
        foreach($result as $row)
        {
            echo '<div class="item" style="padding:5px 20px;float:left;min-width:200px;">';
            echo '<span class="id" style="display:none;">' . $row['id'] . '</span>';
            echo '<span class="type" style="display:none;">' . $row['type'] . '</span>';
            echo '<span class="name" style="color:#000000">' . $row['name'] . '</span>';
            echo ' (<span class="count" style="">' . $row['count'] . '</span>)';
            echo '<span class="control" style="display:none;margin-left:10px;">';

            if($row['type'] == '4')
            {
                echo '<a class="learn_blueprint" href="#">学习</a> | ';
            }

            echo '<a class="sell" href="#">出售</a>';
            // echo ' | <a href="' . site_url('action/market/sell/' . $row['id']) . '">拍卖行</a>';
            if($row['is_locked'] == '1')
            {
                echo ' | <a class="unlock" href="' . site_url('role/item/unlock/' . $row['id']) . '">解锁</a>';
            }
            else
            {
                echo ' | <a class="lock" href="' . site_url('role/item/lock/' . $row['id']) . '">上锁</a>';
            }
            echo ' | <a class="destroy" href="' . site_url('role/item/destroy/' . $row['id']) . '">销毁</a>';
            echo '</span>';
            echo '</div>';
        }
        ?>
        <div style="clear:both;"></div>
        </div>
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
    </div>
    <div class="clear"></div>
    <script src="<?php echo base_url('resources/js/jquery-ui-1.10.4.custom.min.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('resources/js/const.config.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('resources/js/item.js'); ?>" type="text/javascript"></script>