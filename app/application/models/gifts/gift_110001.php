<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
require_once ('IGift.php');
/*
 * 爪击
 * 对目标造成120%伤害
 */
class Gift_110001 implements IGift
{
	private $gift_name = '奸商';
	private $actions = array( 'after_billing_buy', 'after_billing_sell' );

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
		if(isset($parameter['action']) && isset($parameter['price']))
		{
			if($parameter['action'] == 'buy')
			{
				$parameter['price'] *= .9;
			}
			else
			{
				$parameter['price'] *= 1.1;
			}
			$parameter['price'] = intval($parameter['price']);
		}
	}
}

?>