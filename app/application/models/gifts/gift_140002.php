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
	private $actions = array( 'after_make_equipment', 'after_upgrade_equipment', 'after_enchant_equipment' );

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
		if(isset($parameter['price']))
		{
			$parameter['price'] *= .9;
			$parameter['price'] = intval($parameter['price']);
		}
	}
}

?>