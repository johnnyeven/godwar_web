<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
class Test extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$attacker = array(
			'gold'		=>	1600
		);
		var_dump($attacker);
		$this->load->library('Gift');
		$hook = array(
			'action'		=>	'after_billing_buy',
			'gift_id'		=>	'110001',
			'parameter'		=>	&$attacker
		);
		$this->gift->hook($hook);
		$this->gift->call_hook('after_billing_buy');

		var_dump($attacker);
	}
}
?>