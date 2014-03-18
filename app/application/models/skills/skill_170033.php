<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
require_once ('ISkill.php');
/*
 * 爪击
 * 对目标造成120%伤害
 */
class Skill_170033 extends CI_Model implements ISkill
{
	private $skill_name = '灵能召唤';

	public function __construct()
	{
		parent::__construct();
	}

	public function execute( & $attacker, & $defender )
	{
		if(!isset($attacker['status']) || !is_array($attacker['status']))
		{
			$attacker['status'] = array();
		}
		$attacker['status']['300014'] = array( 3, null );
		
		$parameter = array(
			'skill'			=>	$this->skill_name
		);
		return $parameter;
	}
}

?>