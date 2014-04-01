<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
require_once ('IGift.php');
/*
 * 爪击
 * 对目标造成120%伤害
 */
class Gift_110011 implements IGift
{
	private $gift_name = '炼金术的奥秘';
	//升级后计算属性值以后
	private $actions = array( 'before_settle_battle' );

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
		if(isset($parameter['item_drop']))
		{
			$parameter['item_drop'] += 0.1;
		}
	}
}

?>