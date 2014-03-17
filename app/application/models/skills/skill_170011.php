<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
require_once ('ISkill.php');
/*
 * 爪击
 * 对目标造成120%伤害
 */
class Skill_170011 extends CI_Model implements ISkill
{
	private $skill_name = '咆哮';

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
		$attacker['status']['300009'] = array( 2, null );
		return null;
	}
}

?>