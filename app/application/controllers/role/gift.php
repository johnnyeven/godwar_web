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

	public function activate($id)
	{
		if(!empty($id))
		{
			if($this->currentRole->role['gift_point'] > 0)
			{
				$this->load->library('Mongo_db');
				$parameter = array(
					'id'	=>	$this->currentRole->role['race']
				);
				$result = $this->mongo_db->where($parameter)->get('race');
				$exist = false;
				$level_limit = 0;
				if(!empty($result))
				{
					$result = $result[0];
					$id = intval($id);
					foreach($result['gift'] as $gift)
					{
						if($gift['id'] == $id)
						{
							$exist = true;
							$level_limit = $gift['level_limit'];
							break;
						}
					}

					if(!$exist)
					{
						showMessage( MESSAGE_TYPE_ERROR, 'GIFT_ACTIVATE_ERROR_NOT_EXIST', '', 'role/gift', true, 5 );
					}

					if($this->currentRole->role['level'] >= $level_limit)
					{
						array_push($this->currentRole->role['gift'], $id);
						$this->currentRole->role['gift_point']--;
						$this->currentRole->save();

						showMessage( MESSAGE_TYPE_SUCCESS, 'GIFT_ACTIVATE_SUCCESS', '', 'role/gift', true, 5 );
					}
					else
					{
						showMessage( MESSAGE_TYPE_ERROR, 'GIFT_ACTIVATE_ERROR_LEVEL_LIMIT', '', 'role/gift', true, 5 );
					}
				}
				else
				{
					showMessage( MESSAGE_TYPE_ERROR, 'GIFT_ACTIVATE_ERROR_RACE_ERROR', '', 'role/gift', true, 5 );
				}
			}
			else
			{
				showMessage( MESSAGE_TYPE_ERROR, 'GIFT_ACTIVATE_ERROR_POINT_NOT_ENOUGH', '', 'role/gift', true, 5 );
			}
		}
		else
		{
			showMessage( MESSAGE_TYPE_ERROR, 'GIFT_ACTIVATE_ERROR_NO_PARAM', '', 'role/gift', true, 5 );
		}
	}
}