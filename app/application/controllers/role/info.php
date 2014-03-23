<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
class Info extends CI_Controller
{
	private $pageName = 'role/info';
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
		$this->load->config('const.config');
		$raceConfig = $this->config->item('const_race');
		$jobConfig = $this->config->item('const_job');
		$parameter = array (
				'role' => $this->currentRole 
		);

		$this->load->model( 'utils/render' );
		$this->render->render( $this->pageName, $parameter );
	}
}

?>