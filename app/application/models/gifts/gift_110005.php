<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
require_once ('IGift.php');
/*
 * 爪击
 * 对目标造成120%伤害
 */
class Gift_110005 implements IGift
{
	private $gift_name = '生命';
	//升级后计算属性值以后
	private $actions = array( 'after_level_up' );

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

	public function execute( & $parameter )
	{
		if(isset($parameter['health_max']))
		{
			$parameter['health_max'] *= 1.05;
			$parameter['health_max'] = intval($parameter['health_max']);
			$parameter['health'] *= 1.05;
			$parameter['health'] = intval($parameter['health']);
		}
	}
}

?>