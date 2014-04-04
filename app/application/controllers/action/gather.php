<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Gather extends CI_Controller {
	private $pageName = 'action/gather';
	private $user = null;
	private $currentRole = null;

	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'utils/check_user', 'check' );
		$this->user = $this->check->validate ();
		$this->currentRole = $this->check->check_role ();

		$this->load->library('Gift');
	}

	public function index() {
		$this->currentRole->role['current_action'] = 2;
		$this->currentRole->save();

		$parameter = array (
				'role' => $this->currentRole->role
		);
		$this->load->model ( 'utils/render' );
		$this->render->render ( $this->pageName, $parameter );
	}

	public function request_gather()
	{
		header('Content-type:text/json');
		$this->load->model('utils/return_format');

		$mem_usage_pre = memory_get_usage();
		if($this->currentRole->role['current_action'] == '2')
		{
			$time = time();
			if ($this->currentRole->role ['next_gathertime'] < $time)
			{
				$map_id = intval($this->currentRole->role['map_id']);
				$this->load->library('Mongo_db');
				$parameter = array(
					'id'		=>	$map_id
				);
				$result = $this->mongo_db->where($parameter)->get('map');
				if(!empty($result))
				{
					$result = $result[0];
					$this->load->helper('array');
					$id = intval(rate_random_element($result['gather']));
					$parameter = array(
						'id'	=>	$id
					);
					$item = $this->mongo_db->where($parameter)->get('item');
					if(!empty($item))
					{
						$item = $item[0];
						$role_id = $this->currentRole->role['id'];
						$this->load->model('mitem');
						$parameter = array(
							'id'		=>	$id,
							'role_id'	=>	$role_id
						);
						$result = $this->mitem->read($parameter);
						if(!empty($result))
						{
							$sql = "UPDATE `items` SET `count`=`count`+1 WHERE `id`={$id} AND `role_id`={$role_id}";
							$this->mitem->db()->query($sql);
						}
						else
						{
							$parameter['name'] = $item['name'];
							$parameter['type'] = $item['type'];
							$parameter['count'] = 1;
							$parameter['price'] = $item['price'];
							$this->mitem->create($parameter);
						}

						$this->load->config('base.config');
						$gather_rest_time = $this->config->item('base_gather_rest_time');
						$this->currentRole->role['gathertime'] = $time;
						$this->currentRole->role['next_gathertime'] = $time + $gather_rest_time;
						$this->currentRole->save();

						$json = array(
							'code'		=>	GATHER_SUCCESS,
							'params'	=>	array(
								'id'				=>	$id,
								'name'				=>	$item['name'],
								'timestamp'			=>	$time,
								'next_battletime'	=>	$this->currentRole->role ['next_gathertime']
							)
						);
					}
					else
					{
						$json = array(
							'code'		=>	GATHER_ERROR_ITEM_NOT_EXIST,
							'params'	=>	array(
								'id'	=>	$id
							)
						);
					}
				}
				else
				{
					$json = array(
						'code'		=>	GATHER_ERROR_MAP_NOT_EXIST
					);
				}
			}
			else
			{
				$json = array(
					'code'		=>	GATHER_ERROR_NOT_TIME,
					'params'	=>	array(
						'timestamp'			=>	$time,
						'next_battletime'	=>	$this->currentRole->role ['next_gathertime']
					)
				);
			}
		}
		else
		{
			$json = array(
				'code'		=>	GATHER_ERROR_CONFLICT
			);
		}

		echo $this->return_format->format($json);
	}
}

?>