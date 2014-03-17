<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
require_once ('IStatus.php');
/*
 * 爪击
 * 对目标造成120%伤害
 */
class Status_300009 extends CI_Model implements IStatus
{
	private $name = '咆哮';

	public function __construct()
	{
		parent::__construct();
	}

	public function execute( & $target, & $parameter )
	{
		if(!isset($target['crit_origin']))
		{
			$target['crit_origin'] = $target['crit'];
		}
		$target['crit'] = $target['crit_origin'] * 1.3;
		$target['crit'] = ceil($target['crit']);

		// $r = array(
		// 	'skill'		=>	$this->name,
		// 	'damage'	=>	$damage
		// );
		return null;
	}

	public function destroy(& $target)
	{
		if(isset($target['crit_origin']))
		{
			$target['crit'] = $target['crit_origin'];
		}
		unset($target['crit_origin']);
	}
}

?>