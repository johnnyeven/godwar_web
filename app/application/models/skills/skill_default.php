<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
require_once ('ISkill.php');
/*
 * 爪击
 * 对目标造成120%伤害
 */
class Skill_default extends CI_Model implements ISkill
{
	private $skill_name = '';

	public function __construct()
	{
		parent::__construct();
	}

	public function execute( &$attacker, &$defender )
	{
		$rand = rand ( 0, 100000 ) / 100000;
		$damageResult = intval ( (($attacker ['atk'] - $attacker ['atk_min']) * $rand + $attacker ['atk_min']) * $levelFix * (1 - $defender ['def_percent']) );
		$parameter = array (
				'skill'		=>	$this->skill_name,
				'damage'	=>	$damageResult 
		);
		var_dump($attacker);
		var_dump($defender);
		exit();
		return $parameter;
	}
}

?>