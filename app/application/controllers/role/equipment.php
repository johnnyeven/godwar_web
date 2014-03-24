<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
class Equipment extends CI_Controller
{
	private $pageName = 'role/equipment';
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
		$this->load->model('mequipment');
		$parameter = array(
			'role_id'	=>	$this->currentRole->role['id']
		);
		$result = $this->mequipment->read($parameter);

		$data = array(
			'equipments'	=>	$result
		);

		$this->load->model( 'utils/render' );
		$this->render->render( $this->pageName, $data );
	}
}

?>