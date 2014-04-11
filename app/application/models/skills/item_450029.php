<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
require_once ('IItem.php');
/*
 * 爪击
 * 对目标造成120%伤害
 */
class Item_450029 extends CI_Model implements IItem
{
	private $name = '小型活力药水';

	public function __construct()
	{
		parent::__construct();
	}

	public function execute( & $target, & $parameter )
	{
		if($parameter == 'all' || $parameter == 'vitality')
		{
			$target['vitality'] += 30;
			$target['vitality'] = min(100, $target['vitality']);
		}
	}
}

?>