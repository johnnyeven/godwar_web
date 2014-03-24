<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
class Test extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$attacker = array();
		var_dump($attacker);
		$this->load->library('Gift');
		$hook = array(
			'action'		=>	'after_billing_buy',
			'gift_id'		=>	'110001'
		);
		$this->gift->hook($hook);

		$attacker['gold'] = 1600;
		var_dump($attacker);
		$this->gift->call_hook('after_billing_buy', $attacker);

		var_dump($attacker);
	}

	public function test123()
	{
		$param = array(
			array(
				'id'			=>	230001,
				'name'			=>	'祈祷术',
				'comment'		=>	'防御加成10%，持续3轮',
				'level_limit'	=>	10,
				'skill_limit'	=>	'',
				'is_passive'	=>	0
			),
			array(
				'id'			=>	230002,
				'name'			=>	'圣光术',
				'comment'		=>	'对目标造成120%魔法伤害，如果目标生命值低于50%，则造成150%魔法伤害',
				'level_limit'	=>	15,
				'skill_limit'	=>	'',
				'is_passive'	=>	0
			),
			array(
				'id'			=>	230003,
				'name'			=>	'庇护',
				'comment'		=>	'对队友及自己防御加成5%、魔防加成5%、闪避加成5%',
				'level_limit'	=>	20,
				'skill_limit'	=>	'',
				'is_passive'	=>	0
			),
			array(
				'id'			=>	230004,
				'name'			=>	'圣言专精',
				'comment'		=>	'被动技能，魔法伤害提升2%',
				'level_limit'	=>	25,
				'skill_limit'	=>	'',
				'is_passive'	=>	1
			),
			array(
				'id'			=>	230005,
				'name'			=>	'皮甲专精',
				'comment'		=>	'被动技能，防御加成2%',
				'level_limit'	=>	27,
				'skill_limit'	=>	'',
				'is_passive'	=>	1
			),
			array(
				'id'			=>	230006,
				'name'			=>	'治疗术',
				'comment'		=>	'恢复自己生命值4%',
				'level_limit'	=>	29,
				'skill_limit'	=>	'',
				'is_passive'	=>	0
			)
		);

		echo json_encode($param);
	}
}
?>