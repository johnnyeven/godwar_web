<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Alchemy extends CI_Controller
{
	private $pageName = 'action/alchemy';
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
		$time = time();

		$this->load->model('malchemy_queue');
		$key = array(
			'role_id'		=>	$this->currentRole->role['id'],
			'endtime <='	=>	time()
		);
		$parameter = array(
			'status'	=>	1
		);
		$this->malchemy_queue->update($key, $parameter);

		$this->load->model('malchemy');
		$parameter = array(
			'role_id'	=>	$this->currentRole->role['id']
		);
		$result = $this->malchemy->read($parameter);
		$parameter = array(
			'role_id'	=>	$this->currentRole->role['id']
		);
		$extension = array(
			'order_by'	=>	array('endtime', 'asc')
		);
		$queue_result = $this->malchemy_queue->read($parameter, $extension);

		$this->load->model('mitem');
		$parameter = array(
			'role_id'	=>	$this->currentRole->role['id']
		);
		$item_result = $this->mitem->read($parameter);

		$data = array(
			'role'		=>	$this->currentRole->role,
			'result'	=>	$result,
			'queue'		=>	$queue_result,
			'items'		=>	$item_result
		);
		$this->load->model( 'utils/render' );
		$this->render->render( $this->pageName, $data );
	}

	public function learn()
	{
		header('Content-type: text/json');
		$this->load->model('utils/return_format');

		$id = $this->input->post('id');

		if(!empty($id))
		{
			$this->load->model('malchemy');
			$key = array(
				'role_id'	=>	$this->currentRole->role['id'],
				'id'		=>	$id	
			);
			$result = $this->malchemy->read($key);

			if(empty($result))
			{
				$this->load->model('mitem');
				$result = $this->mitem->read($key);
				if(!empty($result))
				{
					$result = $result[0];
					if($result['is_locked'] == '1')
					{
						$remain = $result['count'];
						$json = array(
							'code'		=>	ALCHEMY_LEARN_ERROR_LOCKED,
							'params'	=>	array(
								'id'	=>	$id,
								'remain'=>	$remain
							)
						);
					}
					else
					{
						if($result['count'] > 1)
						{
							$role_id = $this->currentRole->role['id'];
							$sql = "UPDATE `items` SET `count`=`count`-1 WHERE `id`={$id} AND `role_id`={$role_id}";
							$this->mitem->db()->query($sql);
							$remain = $result['count'] - 1;
						}
						elseif($result['count'] == 1)
						{
							$this->mitem->delete($key);
							$remain = 0;
						}
						else
						{
							$remain = $result['count'];
							$json = array(
								'code'		=>	ALCHEMY_LEARN_ERROR_NOT_ENOUGH,
								'params'	=>	array(
									'id'	=>	$id,
									'remain'=>	$remain
								)
							);
							echo $this->return_format->format($json);
							exit();
						}

						$key['name'] = $result['name'];
						$this->malchemy->create($key);

						$json = array(
							'code'		=>	ALCHEMY_LEARN_SUCCESS,
							'params'	=>	array(
								'id'	=>	$id,
								'name'	=>	$result['name'],
								'remain'=>	$remain
							)
						);
					}
				}
				else
				{
					$json = array(
						'code'		=>	ALCHEMY_LEARN_ERROR_NOT_EXIST,
						'params'	=>	array(
							'id'	=>	$id
						)
					);
				}
			}
			else
			{
				$json = array(
					'code'		=>	ALCHEMY_LEARN_ERROR_EXIST,
					'params'	=>	array(
						'id'	=>	$id
					)
				);
			}
		}
		else
		{
			$json = array(
				'code'		=>	ALCHEMY_LEARN_ERROR_NO_PARAM,
				'params'	=>	array(
					'id'	=>	$id
				)
			);
		}

		echo $this->return_format->format($json);
	}

	public function info()
	{
		header('Content-type: text/json');
		$this->load->model('utils/return_format');

		$id = intval($this->input->post('id'));

		if(!empty($id))
		{
			$this->load->model('malchemy');
			$key = array(
				'role_id'	=>	$this->currentRole->role['id'],
				'id'		=>	$id	
			);
			$result = $this->malchemy->read($key);

			if(!empty($result))
			{
				$result = $result[0];
				$parameter = array();

				$this->load->library('Mongo_db');
				$param = array(
					'id'	=>	$id
				);
				$result = $this->mongo_db->where($param)->get('alchemy');
				$result = $result[0];

				$json = array(
					'code'		=>	ALCHEMY_INFO_SUCCESS,
					'params'	=>	$result
				);
			}
			else
			{
				$json = array(
					'code'		=>	ALCHEMY_INFO_ERROR_NOT_EXIST,
					'params'	=>	array(
						'id'	=>	$id
					)
				);
			}
		}
		else
		{
			$json = array(
				'code'		=>	ALCHEMY_INFO_ERROR_NO_PARAM,
				'params'	=>	array(
					'id'	=>	$id
				)
			);
		}

		echo $this->return_format->format($json);
	}

	public function build()
	{
		header('Content-type: text/json');
		$this->load->model('utils/return_format');

		$id = intval($this->input->post('id'));

		if(!empty($id))
		{
			$this->load->model('malchemy_queue');
			$key = array(
				'role_id'	=>	$this->currentRole->role['id'],
				'id'		=>	$id	
			);
			$result = $this->malchemy_queue->read($key);

			if(empty($result))
			{
				$this->load->model('malchemy');
				$result = $this->malchemy->read($key);

				if(!empty($result))
				{
					$result = $result[0];
					$parameter = array();

					$this->load->library('Mongo_db');
					$param = array(
						'id'	=>	$id
					);
					$result = $this->mongo_db->where($param)->get('alchemy');
					if(!empty($result))
					{
						$result = $result[0];
						$this->load->model('mitem');

						$role_id = $this->currentRole->role['id'];
						$success = true;
						foreach($result['materials'] as $item)
						{
							$count = $item['cost'];

							$parameter = array(
								'id'		=>	$item['id'],
								'role_id'	=>	$role_id,
								'count >='	=>	$count
							);
							$tmp = $this->mitem->read($parameter);
							if(empty($tmp))
							{
								$success = false;
								break;
							}
						}
						if($success)
						{
							foreach($result['materials'] as $item)
							{
								$count = $item['cost'];
								$item_id = $item['id'];
								$sql = "UPDATE `items` SET `count`=`count`-{$count} WHERE `id`={$item_id} AND `role_id`={$role_id}";
								$this->mitem->db()->query($sql);
							}

							$time = time();
							$endtime = $time + $result['costtime'];
							$parameter = array(
								'role_id'		=>	$role_id,
								'id'			=>	$id,
								'product_id'	=>	$result['product']['id'],
								'name'			=>	$result['product']['name'],
								'starttime'		=>	$time,
								'endtime'		=>	$endtime
							);
							$this->malchemy_queue->create($parameter);

							$json = array(
								'code'		=>	ALCHEMY_BUILD_SUCCESS,
								'params'	=>	$parameter
							);
						}
						else
						{
							$json = array(
								'code'		=>	ALCHEMY_BUILD_ERROR_NOT_ENOUGH,
								'params'	=>	array(
									'id'	=>	$result
								)
							);
						}
					}
				}
				else
				{
					$json = array(
						'code'		=>	ALCHEMY_BUILD_ERROR_NOT_EXIST,
						'params'	=>	array(
							'id'	=>	$id
						)
					);
				}
			}
			else
			{
				$json = array(
					'code'		=>	ALCHEMY_BUILD_ERROR_MAX_QUEUE,
					'params'	=>	array(
						'id'	=>	$id
					)
				);
			}
		}
		else
		{
			$json = array(
				'code'		=>	ALCHEMY_BUILD_ERROR_NO_PARAM,
				'params'	=>	array(
					'id'	=>	$id
				)
			);
		}

		echo $this->return_format->format($json);
	}

	public function receive()
	{
		header('Content-type: text/json');
		$this->load->model('utils/return_format');

		$id = intval($this->input->post('id'));

		if(!empty($id))
		{
			$this->load->model('malchemy_queue');
			$key = array(
				'role_id'	=>	$this->currentRole->role['id'],
				'id'		=>	$id	
			);
			$result = $this->malchemy_queue->read($key);

			if(!empty($result))
			{
				$result = $result[0];
				$product_id = intval($result['product_id']);

				$this->load->library('Mongo_db');
				$parameter = array(
					'id'	=>	$product_id
				);
				$item = $this->mongo_db->where($parameter)->get('item');
				$item = $item[0];

				$this->load->model('mitem');
				$parameter = array(
					'role_id'	=>	$this->currentRole->role['id'],
					'id'		=>	$product_id
				);
				$result = $this->mitem->read($parameter);
				if(!empty($result))
				{
					$role_id = $this->currentRole->role['id'];
					$sql = "UPDATE `items` SET `count`=`count`+1 WHERE `id`={$product_id} AND `role_id`={$role_id}";
					$this->mitem->db()->query($sql);
				}
				else
				{
					$params = array(
						'name'				=>	$item['name'],
						'type'				=>	$item['type'],
						'count'				=>	1,
						'price'				=>	$item['price'],
						'comment'			=>	$item['comment'],
						'remain_time'		=>	$item['remain_time'],
						'atk_inc'			=>	$item['atk_inc'],
						'atk_inc_unit'		=>	$item['atk_inc_unit'],
						'def_inc'			=>	$item['def_inc'],
						'def_inc_unit'		=>	$item['def_inc_unit'],
						'mdef_inc'			=>	$item['mdef_inc'],
						'mdef_inc_unit'		=>	$item['mdef_inc_unit'],
						'health_max_inc'	=>	$item['health_max_inc'],
						'health_max_inc_unit'=>	$item['health_max_inc_unit'],
						'hit_inc'			=>	$item['hit_inc'],
						'hit_inc_unit'		=>	$item['hit_inc_unit'],
						'crit_inc'			=>	$item['crit_inc'],
						'crit_inc_unit'		=>	$item['crit_inc_unit'],
						'flee_inc'			=>	$item['flee_inc'],
						'flee_inc_unit'		=>	$item['flee_inc_unit'],
						'exp_inc'			=>	$item['exp_inc'],
						'exp_inc_unit'		=>	$item['exp_inc_unit'],
						'gold_inc'			=>	$item['gold_inc'],
						'gold_inc_unit'		=>	$item['gold_inc_unit'],
						'vitality_inc'		=>	$item['vitality_inc'],
						'vitality_inc_unit'	=>	$item['vitality_inc_unit'],
						'make_item_id'		=>	$item['make_item_id'],
					);
					$parameter = array_merge($parameter, $params);
					$this->mitem->create($parameter);
				}
				$this->malchemy_queue->delete($key);

				$json = array(
					'code'		=>	ALCHEMY_RECEIVE_SUCCESS,
					'params'	=>	array(
						'id'	=>	$id,
						'name'	=>	$item['name']
					)
				);
			}
			else
			{
				$json = array(
					'code'		=>	ALCHEMY_RECEIVE_ERROR_NOT_EXIST,
					'params'	=>	array(
						'id'	=>	$id
					)
				);
			}
		}
		else
		{
			$json = array(
				'code'		=>	ALCHEMY_RECEIVE_ERROR_NO_PARAM,
				'params'	=>	array(
					'id'	=>	$id
				)
			);
		}

		echo $this->return_format->format($json);
	}
}

?>