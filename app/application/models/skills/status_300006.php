<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
require_once ('IStatus.php');
/*
 * 爪击
 * 对目标造成120%伤害
 */
class Status_300006 extends CI_Model implements IStatus
{
	private $name = '厚甲';

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
		$target['def'] = $target['def_origin'] * 1.1;
		$target['def'] = ceil($target['def']);

		if(!isset($target['mdef_origin']))
		{
			$target['mdef_origin'] = $target['mdef'];
		}
		$target['mdef'] = $target['mdef_origin'] * 1.1;
		$target['mdef'] = ceil($target['mdef']);

		$r = array(
			'skill'			=>	$this->name,
			'target'		=>	$target['name'],
			'def_offset'	=>	$target['def'] - $target['def_origin'],
			'mdef_offset'	=>	$target['mdef'] - $target['mdef_origin']
		);
		return $r;
	}

	public function destroy(& $target)
	{
		if(isset($target['def_origin']))
		{
			$target['def'] = $target['def_origin'];
		}
		unset($target['def_origin']);

		if(isset($target['mdef_origin']))
		{
			$target['mdef'] = $target['mdef_origin'];
		}
		unset($target['mdef_origin']);
	}
}

?>