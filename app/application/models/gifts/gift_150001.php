<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
require_once ('IGift.php');
/*
 * 爪击
 * 对目标造成120%伤害
 */
class Gift_150001 implements IGift
{
	private $gift_name = '亡灵契约';
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
		if(isset($parameter['skill_trigger']))
		{
			$parameter['skill_trigger'] *= 1.1;
		}
	}
}

?>