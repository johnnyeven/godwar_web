<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );

class Gift extends CI_Controller
{

	public function __construct()
	{
		parent::__construct ();
	}
	
	public function index()
	{
		$result = array(
			array(
				'id'			=>	160001,
				'name'			=>	'泰坦之墙',
				'comment'		=>	'防御额外加成10%',
				'level_limit'	=>	1,
				'basic'			=>	1
			),
			array(
				'id'			=>	160002,
				'name'			=>	'元素之精华',
				'comment'		=>	'生命额外加成10%',
				'level_limit'	=>	1,
				'basic'			=>	1
			),
			array(
				'id'			=>	160003,
				'name'			=>	'攻击',
				'comment'		=>	'攻击提升5%',
				'level_limit'	=>	7,
				'basic'			=>	0
			),
			array(
				'id'			=>	160004,
				'name'			=>	'防御',
				'comment'		=>	'防御提升5%',
				'level_limit'	=>	7,
				'basic'			=>	0
			),
			array(
				'id'			=>	160005,
				'name'			=>	'生命',
				'comment'		=>	'生命提升5%',
				'level_limit'	=>	9,
				'basic'			=>	0
			),
			array(
				'id'			=>	160011,
				'name'			=>	'炼金术的奥秘',
				'comment'		=>	'战斗后获得炼金术材料的机率增加10%',
				'level_limit'	=>	18,
				'basic'			=>	0
			),
			array(
				'id'			=>	160012,
				'name'			=>	'强化大师',
				'comment'		=>	'强化时有30%机率获得的经验增加50%',
				'level_limit'	=>	19,
				'basic'			=>	0
			),
			array(
				'id'			=>	160013,
				'name'			=>	'伙伴万岁',
				'comment'		=>	'守护灵每次成长经验值增加10%',
				'level_limit'	=>	20,
				'basic'			=>	0
			),
			array(
				'id'			=>	160014,
				'name'			=>	'技能训练',
				'comment'		=>	'技能触发机率增加5%',
				'level_limit'	=>	20,
				'basic'			=>	0
			),
			array(
				'id'			=>	160015,
				'name'			=>	'炼金术大师',
				'comment'		=>	'炼金术获得高阶装备的概率提升10%',
				'level_limit'	=>	20,
				'basic'			=>	0
			)
		);

		echo json_encode($result);
	}
}