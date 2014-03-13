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
				'id'			=>	'210001',
				'name'			=>	'致死打击',
				'comment'		=>	'造成的伤害提升50%',
				'level_limit'	=>	10,
				'skill_limit'	=>	''
			),
			array(
				'id'			=>	'210002',
				'name'			=>	'顺势斩',
				'comment'		=>	'对所有目标造成80%的物理伤害',
				'level_limit'	=>	12,
				'skill_limit'	=>	''
			),
			array(
				'id'			=>	'210003',
				'name'			=>	'刚毅',
				'comment'		=>	'受到的伤害减少10%，持续两轮',
				'level_limit'	=>	17,
				'skill_limit'	=>	''
			),
			array(
				'id'			=>	'210004',
				'name'			=>	'嗜血',
				'comment'		=>	'对目标造成100%伤害，同时回复造成的伤害20%的血量',
				'level_limit'	=>	20,
				'skill_limit'	=>	''
			),
			array(
				'id'			=>	'210005',
				'name'			=>	'重甲精通',
				'comment'		=>	'被动技能，防御加成5%',
				'level_limit'	=>	25,
				'skill_limit'	=>	''
			)
		);

		echo json_encode($result);
	}
}