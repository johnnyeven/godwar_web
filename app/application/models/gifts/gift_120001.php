<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
require_once ('IGift.php');
/*
 * 爪击
 * 对目标造成120%伤害
 */
class Gift_120001 implements IGift
{
	private $gift_name = '身手敏捷';
	private $actions = array( 'before_battle' );

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
		if(isset($parameter['flee']))
		{
			$parameter['flee'] *= 1.1;
			$parameter['flee'] = intval($parameter['flee']);
		}
	}
}

?>