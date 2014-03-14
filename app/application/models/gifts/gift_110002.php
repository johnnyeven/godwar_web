<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
require_once ('IGift.php');
/*
 * 爪击
 * 对目标造成120%伤害
 */
class Gift_110002 implements IGift
{
	private $gift_name = '过人天赋';
	//升级后计算属性值以前
	private $actions = array( 'before_level_up' );

	public function __construct()
	{
		
	}

	public function can_hooked( $action )
	{
		if(!in_array($action, $this->actions))
		{
			return false;
		}
		return true;
	}

	public function execute( &$parameter )
	{
		$attributes = array('atk_base', 'def_base', 'mdef_base', 'hit_base', 'flee_base');
		$attribute = array_rand($attributes);

		if(isset($parameter[$attribute]))
		{
			$parameter[$attribute] += 1;
		}
	}
}

?>