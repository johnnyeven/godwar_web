<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
require_once ('IItem.php');
/*
 * 爪击
 * 对目标造成120%伤害
 */
class Item_450012 extends CI_Model implements IItem
{
	private $name = '中型攻击强化药剂';

	public function __construct()
	{
		parent::__construct();
	}

	public function execute( & $target, & $parameter )
	{
		if($parameter == 'all' || $parameter == 'atk')
		{
			$target['atk'] *= 1.2;
			$target['atk'] = intval($target['atk']);
		}
	}
}

?>