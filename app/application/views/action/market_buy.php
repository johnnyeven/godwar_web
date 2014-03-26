    <div class="left">
    
    </div>
    <div class="right">
        <form action="<?php echo site_url('action/market/buy_submit'); ?>" method="post" enctype="application/x-www-form-urlencoded" name="form" id="form">
        	<input type="hidden" id="id" name="id" value="<?php echo $order['id']; ?>" />
            <p>名字：<?php echo $order['name']; ?></p>
            <p>价格：<?php echo $order['price']; ?></p>
            <p>属性：
                <div style="padding-left:10px;">
                    <?php
                    if($order['atk_base'] > 0)
                    {
                        echo '<p>攻击：' . $order['atk_base'] . '</p>';
                    }
                    if($order['def_base'] > 0)
                    {
                        echo '<p>防御：' . $order['def_base'] . '</p>';
                    }
                    if($order['mdef_base'] > 0)
                    {
                        echo '<p>魔抗：' . $order['mdef_base'] . '</p>';
                    }
                    if($order['health_max_base'] > 0)
                    {
                        echo '<p>生命：' . $order['health_max_base'] . '</p>';
                    }
                    if($order['hit_base'] > 0)
                    {
                        echo '<p>命中：' . $order['hit_base'] . '</p>';
                    }
                    if($order['flee_base'] > 0)
                    {
                        echo '<p>闪避：' . $order['flee_base'] . '</p>';
                    }
                    ?>
                </div>
            </p>
                <?php
                $properties = array('atk', 'def', 'mdef', 'health_max', 'hit', 'flee');
                if(!empty($order['magic_words']))
                {
                    echo '<p>加成：<div style="padding-left:10px;">';
                    $magic_words = json_decode($order['magic_words'], TRUE);
                    foreach($magic_words as $word)
                    {
                        foreach($word['property'] as $key => $property)
                        {
                            if(in_array($key, $properties))
                            {
                                if($word['property'][$key . '_unit'] == 1)
                                {
                                    echo '<p>' . $key . '加成：' . $property;
                                }
                                elseif($word['property'][$key . '_unit'] == 2)
                                {
                                    echo '<p>' . $key . '加成：' . ($property * 100) . '%';
                                }
                            }
                        }
                    }
                    echo '</div></p>';
                }
                ?>
            <p><input type="submit" name="button" id="button" value="确定购买" /></p>
        </form>
    </div>
    <div class="clear"></div>