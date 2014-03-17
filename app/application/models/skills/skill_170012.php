<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
require_once ('ISkill.php');
/*
 * 爪击
 * 对目标造成120%伤害
 */
class Skill_170012 extends CI_Model implements ISkill
{
	private $skill_name = '治愈术';

	public function __construct()
	{
		parent::__construct();
	}

	public function execute( &$attacker, &$defender )
	{
		$damage = ceil(intval($attacker['health']) * .04);
		$attacker['health'] += $damage;
		if($attacker['health'] > $attacker['health_max'])
		{
			$attacker['health'] = $attacker['health_max'];
		}
		
		$parameter = array(
			'skill'			=>	$this->skill_name,
			'damage'		=>	-$damage
		);
		return $parameter;
	}
}

?>