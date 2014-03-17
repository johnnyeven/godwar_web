<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
require_once ('ISkill.php');
/*
 * 爪击
 * 对目标造成120%伤害
 */
class Skill_170009 extends CI_Model implements ISkill
{
	private $skill_name = '灵魂缠绕';

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
		$defender['status']['300007'] = array( 2, null );
		return null;
	}
}

?>