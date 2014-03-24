<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Map extends CI_Controller
{
	private $pageName = 'action/map';
	private $user = null;
	private $currentRole = null;

	public function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'utils/check_user', 'check' );
		$this->user = $this->check->validate ();
		$this->currentRole = $this->check->check_role ();
	}

	public function info($map_id)
	{
		if(empty($map_id))
		{
			$map_id = $this->currentRole->role ['map_id'] ;
		}
		$map_id = intval($map_id);
		
		$this->load->library ( 'Mongo_db' );
		if(!empty($map_id))
		{
			$parameter = array (
					'id' => $map_id 
			);
			$map = $this->mongo_db->where ( $parameter )->get ( 'map' );
			$result = $map [0]['monster'];

			$resultMonster = $this->mongo_db->where_in ( 'id', $result )->order_by ( array (
					'level' => 'asc' 
			) )->get ( 'monster' );
		}
		$maps = $this->mongo_db->get('map');

		$parameter = array (
				'role' 					=>	$this->currentRole->role,
				'maps'					=>	$maps,
				'monsters'				=>	$resultMonster,
				'current_selected_map'	=>	$map [0]
		);
		$this->load->model ( 'utils/render' );
		$this->render->render ( $this->pageName, $parameter );
	}

	public function move($map_id)
	{
		if(!empty($map_id))
		{
			$map_id = intval($map_id);

			$this->load->library ( 'Mongo_db' );
			$parameter = array (
					'id' => $map_id 
			);
			$map = $this->mongo_db->where ( $parameter )->get ( 'map' );

			if(!empty($map))
			{
				$parameter = array(
					'map_id'	=>	$map_id
				);

				$this->load->model ( 'role' );
				$this->role->update ( $this->currentRole->role ['id'], $parameter );

				redirect('action/map/info');
			}
			else
			{
				showMessage( MESSAGE_TYPE_ERROR, 'MAP_ID_INVALID', '', 'action/map/info', true, 5 );
			}
		}
		else
		{
			showMessage( MESSAGE_TYPE_ERROR, 'MAP_ID_INVALID', '', 'action/map/info', true, 5 );
		}
	}
}

?>