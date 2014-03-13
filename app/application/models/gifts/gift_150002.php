<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
require_once ('IGift.php');
/*
 * 爪击
 * 对目标造成120%伤害
 */
class Gift_150002 implements IGift
{
	private $gift_name = '炼金术之奥秘';
	private $actions = array( 'before_alchemy' );

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
		if(is_array($parameter))
		{
			for($i=0; $i<count($parameter); $i++)
			{
				if(is_array($parameter[$i]))
				{
					$parameter[$i]['cost'] *= .9;
					$parameter[$i]['cost'] = ceil($parameter[$i]['cost']);
				}
			}
		}
	}
}

?>