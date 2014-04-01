<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
require_once ('IGift.php');
/*
 * 爪击
 * 对目标造成120%伤害
 */
class Gift_110013 implements IGift
{
	private $gift_name = '伙伴万岁';
	//升级后计算属性值以后
	private $actions = array( 'after_spirit_upgrade' );

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
			$parameter['exp'] *= 1.1;
			$parameter['exp'] = intval($parameter['exp']);
		}
	}
}

?>