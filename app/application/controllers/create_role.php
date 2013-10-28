<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
class Create_role extends CI_Controller
{
	private $pageName = 'create_role';
	private $user = null;

	public function __construct()
	{
		parent::__construct();
		$this->load->model( 'utils/check_user', 'check' );
		$this->user = $this->check->validate();
	}

	public function index()
	{
		$this->load->view( 'create_role' );
	}

	public function submit()
	{
		$roleName = $this->input->post( 'role_name' );
		$roleRace = $this->input->post( 'role_race' );
		
		if ( !empty( $roleName ) && !empty( $roleRace ) )
		{
			//TODO 根据种族获取对应种族的人物初始数据
		}
		else
		{
			showMessage( MESSAGE_TYPE_ERROR, 'ROLE_CREATE_ERROR_NO_PARAM', '', 'create_role', true, 
					5 );
		}
	}
}

?>