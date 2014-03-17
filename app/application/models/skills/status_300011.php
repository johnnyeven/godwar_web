<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
require_once ('IStatus.php');
/*
 * 爪击
 * 对目标造成120%伤害
 */
class Status_300011 extends CI_Model implements IStatus
{
	private $name = '诅咒';

	public function __construct()
	{
		parent::__construct();
	}

	public function execute( & $target, & $parameter )
	{
		if(!isset($target['atk_origin']))
		{
			$target['atk_origin'] = $target['atk'];
		}
		$target['atk'] = $target['atk_origin'] * .7;
		$target['atk'] = ceil($target['atk']);

		// $r = array(
		// 	'skill'		=>	$this->name,
		// 	'damage'	=>	$damage
		// );
		return null;
	}

	public function destroy(& $target)
	{
		if(isset($target['atk_origin']))
		{
			$target['atk'] = $target['atk_origin'];
		}
		unset($target['atk_origin']);
	}
}

?>