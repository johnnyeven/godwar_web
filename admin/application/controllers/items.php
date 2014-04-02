<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );

class Items extends CI_Controller
{

	public function __construct()
	{
		parent::__construct ();
	}
	
	public function index()
	{
		$count = $this->input->get('count');
		$type = $this->input->get('type');

		$list1 = array(
			"红", "赤", "朱", "血", "火", "绯", "橙", "橘", "黄", "金",
			"绿", "碧", "青", "清", "蓝", "紫", "玄", "暗", "黑", "白",
			"光", "素", "雪", "粉", "银", "虹", "彩"
		);

		$list2 = array(
			"纹", "鳞", "形", "花", "样", "貌", "如", "似", "影", "音",
			"吟", "吻", "舌", "尾", "首", "神", "牙", "丁", "岩", "翎",
			"琦", "羽"
		);

		$list3 = array(
			'草', '花', '藤', '蔓', '骨', '露', '丝', '壳', '珠', '香'
		);

		$list4 = array(
			"溃烂的","腐朽的","蓝色的","湿润的","潮湿的","完整的","残缺的",
			"碎裂的","粘稠的","断裂的","空的","闪闪发光的","苦的","破","发热的",
			"奇怪的","非凡的","闪亮的","发光的"
		);

		$list5 = array(
			"根茎","毛","动物皮","花瓣","骨头","头饰","权杖碎片","蛛丝","绒毛",
			"恶魔角","叉子","牙齿","碎石块","不明液体","头盖骨","遗物","项链",
			"马蹄","灵魂碎片","马尾","肋骨","大骨","头发","蜂毒","汗腺","毒腺",
			"徽章","盾牌","粉末","树枝","角","手杖","药品","绷带","刀柄","面罩",
			"盔甲残片","封印石","十字架","金属碎片","齿轮","轴承","机油","燃料",
			"螺丝钉"
		);

		$count = empty($count) ? 100 : intval($count);
		$type = empty($type) ? 1 : intval($type);

		$result = array();
		$list1_count = count($list1);
		$list2_count = count($list2);
		$list3_count = count($list3);
		$list4_count = count($list4);
		$list5_count = count($list5);

		if($type == 1) //草药
		{
			for($i = 0; $i<$count; $i++)
			{
				$item = $list1[rand(0, $list1_count - 1)] . $list2[rand(0, $list2_count - 1)] . $list3[rand(0, $list3_count - 1)];
				array_push($result, $item);
			}
		}
		if($type == 2) //怪掉落
		{
			for($i = 0; $i<$count; $i++)
			{
				$item = $list4[rand(0, $list4_count - 1)] . $list5[rand(0, $list5_count - 1)];
				array_push($result, $item);
			}
		}

		array_unique($result);
		foreach($result as $item)
		{
			echo $item . '<br>';
		}
	}
}