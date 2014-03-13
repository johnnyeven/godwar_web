<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
require_once ('IGift.php');
/*
 * 爪击
 * 对目标造成120%伤害
 */
class Gift_140001 implements IGift
{
	private $gift_name = '致命打击';
	//在战斗前
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
		if(isset($parameter['crit']))
		{
			$parameter['crit'] *= 1.1;
			$parameter['crit'] = intval($parameter['crit']);
		}
	}
}

?>