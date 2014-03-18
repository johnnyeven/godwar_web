<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
require_once ('IStatus.php');
/*
 * 爪击
 * 对目标造成120%伤害
 */
class Status_300012 extends CI_Model implements IStatus
{
	private $name = '瘟疫';

	public function __construct()
	{
		parent::__construct();
	}

	public function execute( & $target, & $parameter )
	{
		$damage = intval($target['health_max'] * .03);
		if($target['health'] < $target['health_max'] * .5)
		{
			$damage *= 2;
		}
		$target['health'] -= $damage;
		$target ['health'] = $target ['health'] < 0 ? 0 : $target ['health'];

		$r = array(
			'skill'		=>	$this->name,
			'target'	=>	$target['name'],
			'damage'	=>	$damage
		);
		return $r;
	}

	public function destroy(& $target)
	{
	}
}

?>