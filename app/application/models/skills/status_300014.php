<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
require_once ('IStatus.php');
/*
 * 爪击
 * 对目标造成120%伤害
 */
class Status_300014 extends CI_Model implements IStatus
{
	private $name = '灵能召唤';

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
		$target['atk'] = $target['atk_origin'] * 1.3;
		$target['atk'] = ceil($target['atk']);

		$r = array(
			'skill'			=>	$this->name,
			'target'		=>	$target['name'],
			'atk_offset'	=>	$target['atk'] - $target['atk_origin']
		);
		return $r;
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