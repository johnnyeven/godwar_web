    <link href="<?php echo base_url('resources/css/alchemy.css'); ?>" rel="stylesheet" type="text/css" />
    <div class="left">
        <h3>已学会的配方</h3>
        <?php foreach ($result as $value)
        {
            echo '<p rel="alchemy_item_' . $value['id'] . '">' . $value['name'] . '</p>';
        }
        ?>
    </div>
    <div class="right">
        <div id="content"></div>
    </div>
    <div class="clear"></div>
    <script src="<?php echo base_url('resources/js/alchemy.js'); ?>" type="text/javascript"></script>