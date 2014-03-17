<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
require_once ('IStatus.php');
/*
 * 爪击
 * 对目标造成120%伤害
 */
class Status_300013 extends CI_Model implements IStatus
{
	private $name = '坚毅';

	public function __construct()
	{
		parent::__construct();
	}

	public function execute( & $target, & $parameter )
	{
		if(!isset($target['def_origin']))
		{
			$target['def_origin'] = $target['def'];
		}
		$target['def'] = $target['def_origin'] * .7;
		$target['def'] = ceil($target['def']);

		// $r = array(
		// 	'skill'		=>	$this->name,
		// 	'damage'	=>	$damage
		// );
		return null;
	}

	public function destroy(& $target)
	{
		if(isset($target['def_origin']))
		{
			$target['def'] = $target['def_origin'];
		}
		unset($target['def_origin']);
	}
}

?>