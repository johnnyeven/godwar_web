<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
require_once ('ISkill.php');
/*
 * 爪击
 * 对目标造成120%伤害
 */
class Skill_170003 extends CI_Model implements ISkill
{
	private $skill_name = '血祭';

	public function __construct()
	{
		parent::__construct();
	}

	public function execute( & $attacker, & $defender )
	{
		$levelFix = 1.3;
		$rand = rand(0, 100000) / 100000;
		$damage = intval((($attacker['atk'] - $attacker['atk_min']) * $rand + $attacker['atk_min']) * $levelFix * (1 - $defender['def_percent']));
		$damage = intval($damage * 1.5);

		if(!isset($attacker['status']) || !is_array($attacker['status']))
		{
			$attacker['status'] = array();
		}
		$attacker['status']['300002'] = array( 1, $damage );

		$parameter = array(
			'skill'			=>	$this->skill_name,
			'damage'		=>	$damage
		);
		return $parameter;
	}
}

?>