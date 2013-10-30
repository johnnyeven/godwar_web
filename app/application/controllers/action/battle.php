<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
class Battle extends CI_Controller
{
	private $pageName = 'action/battle';
	private $user = null;
	private $currentRole = null;

	public function __construct()
	{
		parent::__construct();
		$this->load->model( 'utils/check_user', 'check' );
		$this->user = $this->check->validate();
		$this->currentRole = $this->check->check_role();
	}

	public function index()
	{
		$parameter = array (
				'role' => $this->currentRole 
		);
		$this->load->model( 'utils/render' );
		$this->render->render( $this->pageName, $parameter );
	}
	
	public function request_battle()
	{
		
	}
}

?>