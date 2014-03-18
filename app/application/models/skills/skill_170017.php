<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
require_once ('ISkill.php');
/*
 * 爪击
 * 对目标造成120%伤害
 */
class Skill_170017 extends CI_Model implements ISkill
{
	private $skill_name = '瘟疫';

	public function __construct()
	{
		parent::__construct();
	}

	public function execute( & $attacker, & $defender )
	{
		if(!isset($defender['status']) || !is_array($defender['status']))
		{
			$defender['status'] = array();
		}
		$defender['status']['300012'] = array( 3, null );
		
		$parameter = array(
			'skill'			=>	$this->skill_name,
			'target'		=>	$defender['name']
		);
		return $parameter;
	}
}

?>