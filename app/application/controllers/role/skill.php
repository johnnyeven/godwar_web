<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Skill extends CI_Controller {
	private $pageName = 'role/skill';
	private $user = null;
	private $currentRole = null;

	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'utils/check_user', 'check' );
		$this->user = $this->check->validate ();
		$this->currentRole = $this->check->check_role ();
	}

	public function index() {
		$this->load->library('Mongo_db');
		$result = $this->mongo_db->get('job');
		$parameter = array (
				'role'	=>	$this->currentRole->role,
				'job'	=>	$result
		);
		$this->load->model ( 'utils/render' );
		$this->render->render ( $this->pageName, $parameter );
	}
}