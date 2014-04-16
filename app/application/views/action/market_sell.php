<link href="<?php echo base_url('resources/css/tow_column.common.css'); ?>" rel="stylesheet" type="text/css" />
    	<div class="left">
        
        </div>
        <div class="right">
        	<div class="right_top"></div>
            <div class="right_main">
				<form action="<?php echo site_url('action/market/sell_submit'); ?>" method="post" enctype="application/x-www-form-urlencoded" name="form" id="form">
		        	<input type="hidden" id="id" name="id" value="<?php echo $equipment['id']; ?>" />
		            <p>名字：<?php echo $value['name']; ?></p>
		            <p>价格：<input type="text" name="price" id="price" value="<?php echo $equipment['price']; ?>" /></p>
		            <p>结束时间：
		                <select id="endtime" name="endtime">
		                    <option value="1">一天</option>
		                    <option value="3">三天</option>
		                    <option value="7">一周</option>
		                    <option value="30">一个月</option>
		                    <option value="90">三个月</option>
		                    <option value="365">一年</option>
		                </select>
		            </p>
		            <p><input type="submit" name="button" id="button" value="提交" /></p>
		        </form>
            </div>
            <div class="right_bottom"></div>
        </div>
    	<div class="clear"></div>