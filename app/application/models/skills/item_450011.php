<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
require_once ('IItem.php');
/*
 * 爪击
 * 对目标造成120%伤害
 */
class Item_450011 extends CI_Model implements IItem
{
	private $name = '中型生命强化药剂';

	public function __construct()
	{
		parent::__construct();
	}

	public function execute( & $target, & $parameter )
	{
		if($parameter == 'all' || $parameter == 'health_max' || $parameter == 'health')
		{
			$target['health_max'] *= 1.2;
			$target['health_max'] = intval($target['health_max']);
			$target['health'] *= 1.2;
			$target['health'] = intval($target['health']);
		}
	}
}

?>