<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Rebirth extends CI_Controller
{
	private $pageName = 'action/rebirth';
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
		$parameter = array (
				'role' => $this->currentRole->role
		);
		$this->load->model ( 'utils/render' );
		$this->render->render ( $this->pageName, $parameter );
	}

	public function process()
	{
		header('Content-type: text/json');
		$this->load->model('utils/return_format');

		if($this->currentRole->role['health'] <= 0)
		{
			$this->currentRole->role['health'] = intval($this->currentRole->role['health_max'] * .3);
			$this->currentRole->role['append_status']['400002'] = array(
				'type'		=>	'hook',
				'action'	=>	'before_recover_health',
				'endtime'	=>	time() + 600
			);
			$this->currentRole->save();

			$json = array(
				'code'		=>	REBIRTH_SUCCESS
			);
		}
		else
		{
			$json = array(
				'code'		=>	REBIRTH_ERROR_NOT_DEAD
			);
		}

		echo $this->return_format->format($json);
	}
}

?>