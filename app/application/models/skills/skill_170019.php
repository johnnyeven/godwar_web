<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
require_once ('ISkill.php');
/*
 * 爪击
 * 对目标造成120%伤害
 */
class Skill_170019 extends CI_Model implements ISkill
{
	private $skill_name = '坚毅';

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
		$attacker['status']['300013'] = array( 2, null );
		
		$parameter = array(
			'skill'			=>	$this->skill_name,
			'target'		=>	$attacker['name']
		);
		return $parameter;
	}
}

?>