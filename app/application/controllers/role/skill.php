<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Skill extends CI_Controller
{
	private $pageName = 'role/skill';
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
		$result = $this->mongo_db->get('job');
		$parameter = array (
				'role'	=>	$this->currentRole->role,
				'job'	=>	$result
		);
		$this->load->model ( 'utils/render' );
		$this->render->render ( $this->pageName, $parameter );
	}

	public function learn($id)
	{
		if(!empty($id))
		{
			$this->load->library('Mongo_db');
			$parameter = array(
				'id'	=>	intval($this->currentRole->role['job'])
			);
			$result = $this->mongo_db->where($parameter)->get('job');
			$exist = false;
			if(!empty($result))
			{
				$result = $result[0];
				foreach($result['skill'] as $skill)
				{
					if($skill['id'] == $id)
					{
						$exist = true;
						break;
					}
				}

				if(!$exist)
				{
					return;
				}

				array_push($this->currentRole->role['skill'], $id);
				$this->currentRole->save();

				showMessage( MESSAGE_TYPE_SUCCESS, 'SKILL_LEARN_SUCCESS', '', 'role/skill', true, 5 );
			}
			else
			{
				showMessage( MESSAGE_TYPE_ERROR, 'SKILL_LEARN_ERROR_JOB_ERROR', '', 'role/skill', true, 5 );
			}
		}
		else
		{
			showMessage( MESSAGE_TYPE_ERROR, 'SKILL_LEARN_ERROR_NO_PARAM', '', 'role/skill', true, 5 );
		}
	}

	public function set_main_skill($id)
	{
		if(!empty($id))
		{
			if(in_array($id, $this->currentRole->role['skill']))
			{
				$this->currentRole->role['main_skill'] = $id;
				$this->currentRole->save();

				redirect('role/skill');
			}
			else
			{
				showMessage( MESSAGE_TYPE_ERROR, 'SKILL_SET_MAIN_ERROR_NOT_EXIST', '', 'role/skill', true, 5 );
			}
		}
		else
		{
			showMessage( MESSAGE_TYPE_ERROR, 'SKILL_SET_MAIN_ERROR_NO_PARAM', '', 'role/skill', true, 5 );
		}
	}

	public function unset_main_skill($id)
	{
		if(!empty($id))
		{
			if(in_array($id, $this->currentRole->role['skill']))
			{
				$this->currentRole->role['main_skill'] = '';
				$this->currentRole->save();

				redirect('role/skill');
			}
			else
			{
				showMessage( MESSAGE_TYPE_ERROR, 'SKILL_SET_MAIN_ERROR_NOT_EXIST', '', 'role/skill', true, 5 );
			}
		}
		else
		{
			showMessage( MESSAGE_TYPE_ERROR, 'SKILL_SET_MAIN_ERROR_NO_PARAM', '', 'role/skill', true, 5 );
		}
	}
}