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
			$this->load->library('Mongo_db');
			$parameter = array(
					'id'	=>	$roleRace
			);
			$result = $this->mongo_db->where($parameter)->get('race');
			$result = $result[0];
			
			$baseResult = $this->mongo_db->get('base');
			$baseResult = $baseResult[0];

			$parameter = array(
					'level'	=>	1
			);
			$expResult = $this->mongo_db->where($parameter)->get('exp');
			$expResult = $expResult[0];
			
			$this->load->model('role');
			$time = time();
			$parameter = array(
					'account_id'		=>	$this->user->id,
					'role_name'			=>	$roleName,
					'role_level'		=>	1,
					'role_exp'			=>	0,
					'role_nextexp'		=>	$expResult['nextexp'],
					'role_race'			=>	$roleRace,
					'role_job'			=>	0,
					'role_health_max'	=>	$result['health'],
					'role_health'		=>	$result['health'],
					'role_atk'			=>	$result['atk'],
					'role_def'			=>	$result['def'],
					'role_mdef'			=>	$result['mdef'],
					'role_hit'			=>	$result['hit'],
					'role_flee'			=>	$result['flee'],
					'role_skill_config'	=>	$result['skill'],
					'role_createtime'	=>	$time,
					'role_lasttime'		=>	$time,
					'map_id'			=>	$baseResult['init_map_id']
			);
			if($this->role->create($parameter))
			{
				showMessage( MESSAGE_TYPE_SUCCESS, 'ROLE_CREATE_SUCCESS', '', 'choose_role',
				true, 5 );
			}
		}
		else
		{
			showMessage( MESSAGE_TYPE_ERROR, 'ROLE_CREATE_ERROR_NO_PARAM', '', 'create_role', true, 
					5 );
		}
	}
}

?>