<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
require_once ('IGift.php');
/*
 * 爪击
 * 对目标造成120%伤害
 */
class Gift_140002 implements IGift
{
	private $gift_name = '效率提升';
	//在打造、升级、附魔装备前
	private $actions = array( 'before_make_equipment', 'before_upgrade_equipment', 'before_enchant_equipment' );

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
		if(isset($parameter['gold']))
		{
			$parameter['gold'] *= .9;
			$parameter['gold'] = intval($parameter['gold']);
		}
	}
}

?>