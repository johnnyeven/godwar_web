<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
require_once ('IItem.php');
/*
 * 爪击
 * 对目标造成120%伤害
 */
class Item_450016 extends CI_Model implements IItem
{
	private $name = '中型闪避强化药剂';

	public function __construct()
	{
		parent::__construct();
	}

	public function execute( & $target, & $parameter )
	{
		if($parameter == 'all' || $parameter == 'flee')
		{
			$target['flee'] *= 1.2;
			$target['flee'] = intval($target['flee']);
		}
	}
}

?>