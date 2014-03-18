<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
require_once ('ISkill.php');
/*
 * 爪击
 * 对目标造成120%伤害
 */
class Skill_170014 extends CI_Model implements ISkill
{
	private $skill_name = '闪电术';

	public function __construct()
	{
		parent::__construct();
	}

	public function execute( &$attacker, &$defender )
	{
		$levelFix = 1.3;
		$rand = rand(0, 100000) / 100000;
		$damage = intval((($attacker['atk'] - $attacker['atk_min']) * $rand + $attacker['atk_min']) * $levelFix * (1 - $defender['mdef_percent']));
		$damage = intval($damage * .8);
		
		$parameter = array(
			'skill'			=>	$this->skill_name,
			'target'		=>	$defender['name'],
			'damage'		=>	$damage
		);
		return $parameter;
	}
}

?>