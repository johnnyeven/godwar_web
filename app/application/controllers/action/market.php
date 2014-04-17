<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Market extends CI_Controller
{
	private $pageName = 'action/market';
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

		$this->load->config('const.config');
		$job_list = $this->config->item('const_job');
		$this->load->model('mmarket');
		$parameter = array(
			'starttime <='	=>	$time,
			'endtime >='	=>	$time,
			'status'		=>	1
		);
		$extension = array(
			'order_by'	=>	array('starttime', 'desc')
		);
		$result = $this->mmarket->read($parameter, $extension);

		$data = array(
			'orders'	=>	$result,
			'job_list'	=>	$job_list,
			'role'		=>	$this->currentRole->role
		);
		$this->load->model( 'utils/render' );
		$this->render->render( $this->pageName, $data );
	}

	public function cancel()
	{
		header('Content-type: text/json');
		$this->load->model('utils/return_format');

		$id = $this->input->post('id');

		if(!empty($id))
		{
			$this->load->model('mmarket');
			$parameter = array(
				'id'		=>	$id,
				'role_id'	=>	$this->currentRole->role['id']
			);
			$result = $this->mmarket->read($parameter);
			if(!empty($result))
			{
				$result = $result[0];
				$this->mmarket->delete($id);

				if($result['type'] == 1)
				{
					$this->load->model('mequipment', 'myitem');
				}
				else
				{
					$this->load->model('mitem', 'myitem');
				}
				$property = json_decode($result['property']);
				$this->myitem->create($property);

				$json = array(
					'code'		=>	MARKET_CANCEL_SUCCESS,
					'params'	=>	array(
						'id'	=>	$id,
						'name'	=>	$result['name']
					)
				);
			}
			else
			{
				$json = array(
					'code'		=>	EQUIPMENT_MARKET_ERROR_NOT_EXIST,
					'params'	=>	array(
						'id'	=>	$id
					)
				);
			}
		}
		else
		{
			$json = array(
				'code'		=>	EQUIPMENT_MARKET_ERROR_NO_PARAM
			);
		}

		echo $this->return_format->format($json);
	}

	public function sell_submit()
	{
		header('Content-type: text/json');
		$this->load->model('utils/return_format');

		$id = $this->input->post('id');
		$type = $this->input->post('type');
		$count = $this->input->post('count');
		$price = $this->input->post('price');
		$endtime = $this->input->post('endtime');

		if(!empty($id) && !empty($type) && ($type == '1' || $type == '2') && $count > 0 && $price > 0)
		{
			if($type == '1')
			{
				$this->load->model('mequipment', 'myitem');
			}
			else
			{
				$this->load->model('mitem', 'myitem');
			}

			$key = array(
				'id'		=>	$id,
				'role_id'	=>	$this->currentRole->role['id']
			);
			$result = $this->myitem->read($key);
			if(!empty($result))
			{
				$result = $result[0];
				if($type == '1' && $result['is_equipped'] == '1')
				{
					$json = array(
						'code'		=>	EQUIPMENT_MARKET_ERROR_EQUIPPED,
						'params'	=>	array(
							'id'	=>	$id
						)
					);
					echo $this->return_format->format($json);
					exiy();
				}
				if($result['is_locked'] == '1')
				{
					$json = array(
						'code'		=>	EQUIPMENT_MARKET_ERROR_LOCKED,
						'params'	=>	array(
							'id'	=>	$id
						)
					);
				}
				else
				{
					if(empty($price))
					{
						$price = $result['price'];
					}

					if($type == '1')
					{
						$this->myitem->delete($id);
					}
					else
					{
						if($result['count'] > 1)
						{
							$role_id = $this->currentRole->role['id'];
							$sql = "UPDATE `items` SET `count`=`count`-1 WHERE `id`={$id} AND `role_id`={$role_id}";
							$this->myitem->db()->query($sql);
							$remain = $result['count'] - 1;
						}
						elseif($result['count'] == 1)
						{
							$this->myitem->delete($key);
							$remain = 0;
						}
						else
						{
							$remain = $result['count'];
							$json = array(
								'code'		=>	EQUIPMENT_MARKET_ERROR_NOT_ENOUGH,
								'params'	=>	array(
									'id'	=>	$id,
									'name'	=>	$result['name'],
									'remain'=>	$remain
								)
							);
							echo $this->return_format->format($json);
							exit();
						}
					}

					$time = time();
					$parameter = array(
						'role_id'				=>	$this->currentRole->role['id'],
						'name'					=>	$result['name'],
						'price'					=>	$price,
						'property'				=>	json_encode($result),
						'starttime'				=>	$time,
						'endtime'				=>	$time + $endtime * 86400,
						'status'				=>	1,
						'type'					=>	$type
					);
					$this->load->model('mmarket');
					$this->mmarket->create($parameter);

					$json = array(
						'code'		=>	EQUIPMENT_MARKET_SELL_SUCCESS,
						'params'	=>	array(
							'id'	=>	$id,
							'name'	=>	$result['name']
						)
					);
				}
			}
			else
			{
				$json = array(
					'code'		=>	EQUIPMENT_MARKET_ERROR_NOT_EXIST,
					'params'	=>	array(
						'id'	=>	$id
					)
				);
			}
		}
		else
		{
			$json = array(
				'code'		=>	EQUIPMENT_MARKET_ERROR_NO_PARAM
			);
		}

		echo $this->return_format->format($json);
	}

	public function buy_submit()
	{
		header('Content-type: text/json');
		$this->load->model('utils/return_format');

		$id = $this->input->post('id');

		if(!empty($id))
		{
			$this->load->model('mmarket');
			$parameter = array(
				'id'		=>	$id
			);
			$result = $this->mmarket->read($parameter);
			if(!empty($result))
			{
				$result = $result[0];
				if($result['role_id'] == $this->currentRole->role['id'])
				{
					$json = array(
						'code'		=>	MARKET_ERROR_SELF_ORDER,
						'params'	=>	array(
							'id'	=>	$id
						)
					);
				}
				elseif($result['price'] > $this->currentRole->role['gold'])
				{
					$json = array(
						'code'		=>	MARKET_ERROR_NOT_ENOUGH_GOLD,
						'params'	=>	array(
							'id'	=>	$id
						)
					);
				}
				else
				{
					$this->mmarket->delete($id);

					if($result['type'] == 1)
					{
						$this->load->model('mequipment', 'myitem');
					}
					else
					{
						$this->load->model('mitem', 'myitem');
					}
					$property = json_decode($result['property'], TRUE);
					$property['role_id'] = $this->currentRole->role['id'];
					$this->myitem->create($property);

					$this->load->model('role');
					$db = $this->role->db();
					$db->set('gold', 'gold + ' . $result['price'], FALSE);
					$db->where('id', $result['role_id']);
					$db->update('roles');

					$this->currentRole->role['gold'] -= $result['price'];
					$this->currentRole->save();

					$json = array(
						'code'		=>	MARKET_BUY_SUCCESS,
						'params'	=>	array(
							'id'	=>	$id,
							'name'	=>	$result['name']
						)
					);
				}
			}
			else
			{
				$json = array(
					'code'		=>	MARKET_ERROR_NOT_EXIST,
					'params'	=>	array(
						'id'	=>	$id
					)
				);
			}
		}
		else
		{
			$json = array(
				'code'		=>	MARKET_ERROR_NO_PARAM
			);
		}

		echo $this->return_format->format($json);
	}
}

?>