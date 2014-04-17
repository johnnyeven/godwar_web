<link href="<?php echo base_url('resources/css/tow_column.common.css'); ?>" rel="stylesheet" type="text/css" />
    	<div class="left">
        
        </div>
        <div class="right">
        	<div class="right_top"></div>
            <div class="right_main">
				<table class="table table-bordered table-striped" border="0" cellspacing="0" cellpadding="5">
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
						if($property['grade'] == 0)
						{
							echo '<span style="color:#000000">' . $property['name'] . '</span>';
						}
						elseif($property['grade'] == 1)
						{
							echo '<span style="color:#0099FF">' . $property['name'] . '</span>';
						}
						elseif($property['grade'] == 2)
						{
							echo '<span style="color:#2BD52B">' . $property['name'] . '</span>';
						}
						elseif($property['grade'] == 3)
						{
							echo '<span style="color:#CC00FF">' . $property['name'] . '</span>';
						}
						elseif($property['grade'] == 4)
						{
							echo '<span style="color:#FF9900">' . $property['name'] . '</span>';
						}
						if($item['role_id'] == $role['id'])
						{
							echo '<a href="' . site_url('action/market/cancel/' . $item['id']) . '"><span class="badge badge-important">取消订单</span></a>';
						}
						else
						{
							echo '<a href="' . site_url('action/market/buy/' . $item['id']) . '"><span class="badge badge-success">购买</span></a>';
						}
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