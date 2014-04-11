<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
require_once ('IItem.php');
/*
 * 爪击
 * 对目标造成120%伤害
 */
class Item_450015 extends CI_Model implements IItem
{
	private $name = '中型命中强化药剂';

	public function __construct()
	{
		parent::__construct();
	}

	public function execute( & $target, & $parameter )
	{
		if($parameter == 'all' || $parameter == 'hit')
		{
			$target['hit'] *= 1.2;
			$target['hit'] = intval($target['hit']);
		}
	}
}

?>