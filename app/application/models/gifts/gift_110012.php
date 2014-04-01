<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
require_once ('IGift.php');
/*
 * 爪击
 * 对目标造成120%伤害
 */
class Gift_110012 implements IGift
{
	private $gift_name = '强化大师';
	//升级后计算属性值以后
	private $actions = array( 'after_upgrade_equipment' );

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
		if(isset($parameter['exp']))
		{
			$rand = rand(0, 100);
			if($rand <= 30)
			{
				$parameter['exp'] *= 1.5;
				$parameter['exp'] = intval($parameter['exp']);
			}
		}
	}
}

?>