<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
require_once ('IGift.php');
/*
 * 爪击
 * 对目标造成120%伤害
 */
class Gift_130001 implements IGift
{
	private $gift_name = '邪恶力量';
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
		if(isset($parameter['exp']))
		{
			$parameter['exp'] *= 1.1;
			$parameter['exp'] = ceil($parameter['exp']);
		}
	}
}

?>