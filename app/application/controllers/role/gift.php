<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );

class Gift extends CI_Controller
{
	private $pageName = 'role/gift';
	private $user = null;
	private $currentRole = null;

	public function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'utils/check_user', 'check' );
		$this->user = $this->check->validate ();
		$this->currentRole = $this->check->check_role ();
	}
	
	public function index()
	{
		$this->load->library('Mongo_db');
		$parameter = array(
			'id'	=>	$this->currentRole->role['race']
		);
		$result = $this->mongo_db->where($parameter)->get('race');
		$parameter = array (
				'role'	=>	$this->currentRole->role,
				'race'	=>	$result[0]
		);
		$this->load->model ( 'utils/render' );
		$this->render->render ( $this->pageName, $parameter );
	}
}