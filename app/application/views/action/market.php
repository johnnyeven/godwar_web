<link href="<?php echo base_url('resources/css/tow_column.common.css'); ?>" rel="stylesheet" type="text/css" />
    	<div class="left">
        
        </div>
        <div class="right">
        	<div class="right_top"></div>
            <div class="right_main">
				<table width="100%" border="0" cellspacing="0" cellpadding="5" style="min-width:600px;">
		          <thead>
		            <tr>
		              <th style="text-align:left;">名称</th>
		              <th style="text-align:left;">类型</th>
		              <th style="text-align:left;">等级</th>
		              <th style="text-align:left;">属性</th>
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
		              echo '<tr>';
		              echo '<td>';
		              if($item['equipment_grade'] == 0)
		              {
		                  echo '<span style="color:#000000">' . $item['equipment_name'] . '</span>';
		              }
		              elseif($item['equipment_grade'] == 1)
		              {
		                  echo '<span style="color:#0099FF">' . $item['equipment_name'] . '</span>';
		              }
		              elseif($item['equipment_grade'] == 2)
		              {
		                  echo '<span style="color:#2BD52B">' . $item['equipment_name'] . '</span>';
		              }
		              elseif($item['equipment_grade'] == 3)
		              {
		                  echo '<span style="color:#CC00FF">' . $item['equipment_name'] . '</span>';
		              }
		              elseif($item['equipment_grade'] == 4)
		              {
		                  echo '<span style="color:#FF9900">' . $item['equipment_name'] . '</span>';
		              }
		              if($item['role_id'] == $role['id'])
		              {
		                  echo ' | <a href="' . site_url('action/market/cancel/' . $item['id']) . '">取消订单</a>';
		              }
		              else
		              {
		                  echo ' | <a href="' . site_url('action/market/buy/' . $item['id']) . '">购买</a>';
		              }
		              echo '</td>';
		              echo '<td>' . $equipment_title[intval($item['equipment_position'])] . '</td>';
		              echo '<td>' . $item['equipment_level'] . '</td>';
		              echo '<td></td>';
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