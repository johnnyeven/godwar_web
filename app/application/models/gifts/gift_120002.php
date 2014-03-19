<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
require_once ('IGift.php');
/*
 * 爪击
 * 对目标造成120%伤害
 */
class Gift_120002 implements IGift
{
	private $gift_name = '灵能的感知';
	//在战斗后结算前
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

	public function execute( &$parameter )
	{
		if(isset($parameter['blue_drop']))
		{
			$parameter['blue_drop'] *= 1.1;
		}
		if(isset($parameter['green_drop']))
		{
			$parameter['green_drop'] *= 1.1;
		}
		if(isset($parameter['purple_drop']))
		{
			$parameter['purple_drop'] *= 1.1;
		}
		if(isset($parameter['gold_drop']))
		{
			$parameter['gold_drop'] *= 1.1;
		}
	}
}

?>