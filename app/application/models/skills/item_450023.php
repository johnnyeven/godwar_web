<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
require_once ('IItem.php');
/*
 * 爪击
 * 对目标造成120%伤害
 */
class Item_450023 extends CI_Model implements IItem
{
	private $name = '大型体质强化药剂';

	public function __construct()
	{
		parent::__construct();
	}

	public function execute( & $target, & $parameter )
	{
		if($parameter == 'all' || $parameter == 'def' || $parameter == 'mdef')
		{
			$target['def'] *= 1.3;
			$target['def'] = intval($target['def']);
			$target['mdef'] *= 1.3;
			$target['mdef'] = intval($target['mdef']);
		}
	}
}

?>