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

			$gifts = array();
			$gift_all = $result['gift'];

			foreach($gift_all as $key => $value)
			{
				if($value['basic'] == 1)
				{
					array_push($gifts, $value['id']);
				}
			}
			
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
					'account_id'			=>	$this->user['id'],
					'name'					=>	$roleName,
					'level'					=>	1,
					'exp'					=>	0,
					'nextexp'				=>	$expResult['nextexp'],
					'race'					=>	$roleRace,
					'job'					=>	0,
					'health_base'			=>	$result['health'],
					'health_max'			=>	$result['health'],
					'health'				=>	$result['health'],
					'atk_base'				=>	$result['atk'],
					'atk'					=>	$result['atk'],
					'def_base'				=>	$result['def'],
					'def'					=>	$result['def'],
					'mdef_base'				=>	$result['mdef'],
					'mdef'					=>	$result['mdef'],
					'hit_base'				=>	$result['hit'],
					'hit'					=>	$result['hit'],
					'crit_base'				=>	$result['crit'],
					'crit'					=>	$result['crit'],
					'flee_base'				=>	$result['flee'],
					'flee'					=>	$result['flee'],
					'skill_trigger_base'	=>	0.3,
					'skill_trigger'			=>	0.3,
					'skill'					=>	'[]',
					'main_skill'			=>	'',
					'passive_skill'			=>	'[]',
					'gift'					=>	json_encode($gifts),
					'createtime'			=>	$time,
					'lasttime'				=>	$time,
					'map_id'				=>	$baseResult['init_map_id'],
					'append_status'			=>	'[]'
			);
			if($this->role->create($parameter))
			{
				$role_id = $this->role->db()->insert_id();

				$this->load->model('mthirdpart');
				$parameter = array(
					'role_id'	=>	$role_id
				);
				if($this->mthirdpart->create($parameter))
				{
					showMessage( MESSAGE_TYPE_SUCCESS, 'ROLE_CREATE_SUCCESS', '', 'choose_role',
					true, 5 );
				}
				else
				{
					showMessage( MESSAGE_TYPE_ERROR, 'ROLE_CREATE_ERROR_DATABASE_FAIL', '', 'create_role',
					true, 5 );
				}
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