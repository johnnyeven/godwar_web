<link href="<?php echo base_url('resources/css/tow_column.common.css'); ?>" rel="stylesheet" type="text/css" />
    	<div class="left">
        
        </div>
        <div class="right">
        	<div class="right_top"></div>
            <div class="right_main">
				<table id="market_list" class="table table-bordered table-striped" border="0" cellspacing="0" cellpadding="5">
		          <thead>
		            <tr>
		              <th style="text-align:left;">名称</th>
		              <th style="text-align:left;">类型</th>
		              <th style="text-align:left;">卖价</th>
		              <th style="text-align:left;">结束时间</th>
		            </tr>
		          </head>
		          <tbody>
		            <?php
		            $equipment_title = array(
		              1 =>  '武器',
		              2 =>  '头盔',
		              3 =>  '护手',
		              4 =>  '盔甲',
		              5 =>  '腰带',
		              6 =>  '鞋子',
		              7 =>  '戒指',
		              8 =>  '项链'
		            );
		            foreach($orders as $item)
		            {
		            	$property = json_decode($item['property'], TRUE);
						echo '<tr>';
						echo '<td>';
						echo '<span class="id" style="display:none;">' . $item['id'] . '</span>';
						if($property['grade'] == 0)
						{
							echo '<span class="name">' . $property['name'] . '</span>';
						}
						elseif($property['grade'] == 1)
						{
							echo '<span class="name color-blue">' . $property['name'] . '</span>';
						}
						elseif($property['grade'] == 2)
						{
							echo '<span class="name color-green">' . $property['name'] . '</span>';
						}
						elseif($property['grade'] == 3)
						{
							echo '<span class="name color-purple">' . $property['name'] . '</span>';
						}
						elseif($property['grade'] == 4)
						{
							echo '<span class="name color-orange">' . $property['name'] . '</span>';
						}

						echo '<div class="equipment-item-detail">';
						if($item['type'] == '1')
						{
				            if($property['grade'] == 0)
				            {
				                echo '<p class="font-size-16 bold">' . $property['name'];
				            }
				            elseif($property['grade'] == 1)
				            {
				                echo '<p class="color-blue font-size-16 bold">' . $property['name'];
				            }
				            elseif($property['grade'] == 2)
				            {
				                echo '<p class="color-green font-size-16 bold">' . $property['name'];
				            }
				            elseif($property['grade'] == 3)
				            {
				                echo '<p class="color-purple font-size-16 bold">' . $property['name'];
				            }
				            elseif($property['grade'] == 4)
				            {
				                echo '<p class="color-orange font-size-16 bold">' . $property['name'];
				            }
				            if($property['upgrade_level'] > 0) echo ' (+' . $property['upgrade_level'] . ')</p>';
				            echo '<p>' . $equipment_title[intval($property['position'])] . '</p>';
				            if($property['level'] > $role['level'])
				            {
								echo '<p class="color-red">' . $property['level'] . '级</p>';
				            }
				            else
				            {
				            	echo '<p>' . $property['level'] . '级</p>';
				            }
				            echo '<p>最高强化等级：' . $property['upgrade_level_max'] . '</p>';
				            echo '<hr>';
				            if($property['atk_base'] > 0) echo '<p>攻击：' .  $property['atk_base'] . '</p>';
				            if($property['def_base'] > 0) echo '<p>防御：' .  $property['def_base'] . '</p>';
				            if($property['mdef_base'] > 0) echo '<p>魔抗：' .  $property['mdef_base'] . '</p>';
				            if($property['health_max_base'] > 0) echo '<p>生命：' .  $property['health_max_base'] . '</p>';
				            if($property['hit_base'] > 0) echo '<p>命中：' .  $property['hit_base'] . '</p>';
				            if($property['flee_base'] > 0) echo '<p>闪避：' .  $property['flee_base'] . '</p>';
				            echo '<hr>';
				            $magics = json_decode($property['magic_words'], TRUE);
				            foreach($magics as $magic)
				            {
				            	if($magic['property']['atk'] > 0) echo '<p>攻击加成：' . ($magic['property']['atk_unit'] == 1 ? $magic['property']['atk'] : ($magic['property']['atk'] * 100) . '%') . '</p>';
				            	if($magic['property']['def'] > 0) echo '<p>防御加成：' . ($magic['property']['def_unit'] == 1 ? $magic['property']['def'] : ($magic['property']['def'] * 100) . '%') . '</p>';
				            	if($magic['property']['mdef'] > 0) echo '<p>魔抗加成：' . ($magic['property']['mdef_unit'] == 1 ? $magic['property']['mdef'] : ($magic['property']['mdef'] * 100) . '%') . '</p>';
				            	if($magic['property']['health_max'] > 0) echo '<p>生命加成：' . ($magic['property']['health_max_unit'] == 1 ? $magic['property']['health_max'] : ($magic['property']['health_max'] * 100) . '%') . '</p>';
				            	if($magic['property']['hit'] > 0) echo '<p>命中加成：' . ($magic['property']['hit_unit'] == 1 ? $magic['property']['hit'] : ($magic['property']['hit'] * 100) . '%') . '</p>';
				            	if($magic['property']['flee'] > 0) echo '<p>闪避加成：' . ($magic['property']['flee_unit'] == 1 ? $magic['property']['flee'] : ($magic['property']['flee'] * 100) . '%') . '</p>';
				            }
				            if(!empty($magics)) echo '<hr>';
				            $jobs = json_decode($property['job'], TRUE);
				            echo '<p>';
				            foreach($jobs as $job)
				            {
				            	if($job == 99)
				            	{
				            		echo '<span>全职业</span>';
				            	}
				            	elseif($job == $role['job'])
				            	{
				            		echo '<span>' . $job_list['job_' . $job] . '</span> ';
				            	}
				            	else
				            	{
				            		echo '<span class="color-red">' . $job_list['job_' . $job] . '</span> ';
				            	}
				            }
				            echo '</p>';
				        }
				        else
				        {
				            echo '<p class="font-size-16 bold">' . $property['name'] . '</p>';
				            echo '<p>' . $property['comment'] . '</p>';
				        }
			            echo '</div>';

			            echo '<ul class="menu" style="display:none;">';
						if($item['role_id'] == $role['id'])
						{
							echo '<li><a class="cancel" href=""><span class="ui-icon ui-icon-closethick"></span>取消订单</a>';
						}
						else
						{
							echo '<li><a class="buy" href="' . site_url('action/market/buy/' . $item['id']) . '"><span class="ui-icon ui-icon-cart"></span>购买</a>';
						}
			            echo '</ul>';

						echo '</td>';
						echo '<td>';
						if($item['type'] == 1)
						{
							echo $equipment_title[intval($property['position'])];
						}
						else
						{
							echo '道具';
						}
						echo '</td>';
						echo '<td>' . $item['price'] . '</td>';
						echo '<td>' . date('Y-m-d H:i:s', $item['endtime']) . '</td>';
						echo '</tr>';
		            }
		            ?>
		          </tbody>
		        </table>
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
<script src="<?php echo base_url('resources/js/market.js'); ?>" type="text/javascript"></script>