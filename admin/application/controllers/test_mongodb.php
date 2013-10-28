<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
class Test_mongodb extends CI_Controller
{

	public function __construct()
	{
		parent::__construct ();
	}
	
	public function index()
	{
		$this->load->library('mongo_db');
	}
}

?>