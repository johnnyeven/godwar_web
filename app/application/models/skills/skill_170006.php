<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
require_once ('ISkill.php');
/*
 * 爪击
 * 对目标造成120%伤害
 */
class Skill_170006 extends CI_Model implements ISkill
{
	private $skill_name = '吸血';

	public function __construct()
	{
		parent::__construct();
	}

	public function execute( & $attacker, & $defender )
	{
		$levelFix = 1.3;
		$rand = rand(0, 100000) / 100000;
		$damage = intval((($attacker['atk'] - $attacker['atk_min']) * $rand + $attacker['atk_min']) * $levelFix * (1 - $defender['def_percent']));
		$damage = intval($damage * 1.2);

		$rand = rand(0, 100);
		if($rand <= 50)
		{
			$attacker['health'] += intval($damage * .2);
			if($attacker['health'] > $attacker['health_max'])
			{
				$attacker['health'] = $attacker['health_max'];
			}
		}

		$parameter = array(
			'skill'			=>	$this->skill_name,
			'target'		=>	$defender['name'],
			'damage'		=>	$damage
		);
		return $parameter;
	}
}

?>