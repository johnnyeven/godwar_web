<link href="<?php echo base_url('resources/css/single.common.css'); ?>" rel="stylesheet" type="text/css" />
        <div class="main">
        	<div class="row-item">
        		<?php if($role['health'] <= 0): ?>
				<div class="row-item font-size-16 color-red bold">你已死亡，请确认以下信息之后，点击下方的“确认重生”按钮</div>
				<div class="row-item">
					<ol>
						<li>只有在死亡的情况下才能进行重生操作。</li>
						<li>重生后会自动增加一条Debuff，“虚弱”状态，持续10分钟，无法自动回复生命值</li>
						<li>VIP3可以免受该死亡惩罚</il>
					</ol>
				</div>
				<div id="progress" class="row-item hidden">
					<div id="progress_bar" class="progress active progress-striped">
                        <div class="bar" style="width:0%"></div>
                    </div>
                    <div class="font-size-16 bold" id="percentage">0%</div>
                    <p class="font-size-16 bold" id="info"></p>
				</div>
				<div class="row-item">
					<a id="btnConfirm" href="#"><button class="btn btn-success">确认重生</button></a>
				</div>
				<?php else: ?>
				<span class="font-size-16 bold">你没有死亡，不能进行重生</span>
				<?php endif; ?>
        	</div>
        </div>
<script src="<?php echo base_url('resources/js/const.config.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('resources/js/rebirth.js'); ?>" type="text/javascript"></script>