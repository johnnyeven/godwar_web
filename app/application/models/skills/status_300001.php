<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
require_once ('IStatus.php');
/*
 * 爪击
 * 对目标造成120%伤害
 */
class Status_300001 extends CI_Model implements IStatus
{
	private $name = '流血';

	public function __construct()
	{
		parent::__construct();
	}

	public function execute( & $target, & $parameter )
	{
		$damage = intval($parameter * .3);
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