<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
require_once ('IStatus.php');
/*
 * 虚弱
 * 由于重生不久身体还很虚弱，无法自动回复生命值
 */
class Status_400002 extends CI_Model
{
	private $name = '虚弱';

	public function __construct()
	{
		parent::__construct();
	}

	public function execute( & $parameter )
	{
		$parameter['recover'] = 0;
	}
}

?>