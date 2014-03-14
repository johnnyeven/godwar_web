<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );

class Skill extends CI_Controller
{

	public function __construct()
	{
		parent::__construct ();
	}
	
	public function index()
	{
		$result = array(
			array(
				'id'			=>	'220001',
				'name'			=>	'火球术',
				'comment'		=>	'对目标造成120%的魔法伤害，30%概率额外造成50%的魔法伤害',
				'level_limit'	=>	10,
				'skill_limit'	=>	''
			),
			array(
				'id'			=>	'220002',
				'name'			=>	'雷电术',
				'comment'		=>	'对所有目标造成100%的魔法伤害',
				'level_limit'	=>	17,
				'skill_limit'	=>	''
			),
			array(
				'id'			=>	'220003',
				'name'			=>	'寒冰盾',
				'comment'		=>	'受到的伤害降低10%，并且由攻击者承受，持续两轮',
				'level_limit'	=>	20,
				'skill_limit'	=>	''
			),
			array(
				'id'			=>	'220004',
				'name'			=>	'法力专精',
				'comment'		=>	'被动技能，魔法伤害提升2%',
				'level_limit'	=>	25,
				'skill_limit'	=>	''
			),
			array(
				'id'			=>	'220005',
				'name'			=>	'轻甲专精',
				'comment'		=>	'被动技能，防御加成2%',
				'level_limit'	=>	27,
				'skill_limit'	=>	''
			)
		);

		echo json_encode($result);
	}
}