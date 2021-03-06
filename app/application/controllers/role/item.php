<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
class Item extends CI_Controller
{
	private $pageName = 'role/item';
	private $user = null;
	private $currentRole = null;

	public function __construct()
	{
		parent::__construct();
		$this->load->model( 'utils/check_user', 'check' );
		$this->user = $this->check->validate();
		$this->currentRole = $this->check->check_role();
	}

	public function index()
	{
		$this->load->model('mitem');
		$parameter = array(
			'role_id'		=>	$this->currentRole->role['id']
		);
		$result = $this->mitem->read($parameter);

		$data = array(
			'role'			=>	$this->currentRole->role,
			'result'		=>	$result
		);

		$this->load->model( 'utils/render' );
		$this->render->render( $this->pageName, $data );
	}

	public function apply()
	{
		header('Content-type: text/json');
		$this->load->model('utils/return_format');

		$id = $this->input->post('id');

		if(!empty($id))
		{
			$id = intval($id);
			$this->load->model('mitem');
			$key = array(
				'role_id'	=>	$this->currentRole->role['id'],
				'id'		=>	$id	
			);
			$result = $this->mitem->read($key);
			if(!empty($result))
			{
				$result = $result[0];
				if($result['is_locked'] == '1')
				{
					$remain = $result['count'];
					$json = array(
						'code'		=>	ITEM_USE_ERROR_LOCKED,
						'params'	=>	array(
							'id'	=>	$id,
							'name'	=>	$result['name'],
							'remain'=>	$remain
						)
					);
				}
				else
				{
					if($result['type'] == '2' || $result['type'] == '3')
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
								'code'		=>	ITEM_USE_ERROR_NOT_ENOUGH,
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

					if($result['type'] == '2')
					{
						//状态药剂
						$this->load->library('Mongo_db');
						$params = array(
							'id'	=>	$id
						);
						$item = $this->mongo_db->where($params)->get('item');
						$item = $item[0];
						$this->currentRole->role['append_status'][$id] = array(
							'type'		=>	'item',
							'endtime'	=>	time() + $item['remain_time']
						);
						$this->currentRole->check_role_status(true);

						$json = array(
							'code'		=>	ITEM_USE_SUCCESS,
							'params'	=>	array(
								'id'	=>	$id,
								'type'	=>	2,
								'name'	=>	$result['name'],
								'remain'=>	$remain
							)
						);
					}
					elseif($result['type'] == '3')
					{
						//恢复药剂
						$item_id = 'item_' . $result['id'];
						$this->load->model('skills/' . $item_id);
						$statu = $this->$item_id->execute($this->currentRole->role);
						$this->currentRole->save();
						
						$json = array(
							'code'		=>	ITEM_USE_SUCCESS,
							'params'	=>	array(
								'id'	=>	$id,
								'type'	=>	3,
								'name'	=>	$result['name'],
								'remain'=>	$remain
							)
						);
					}
					else
					{
						$json = array(
							'code'		=>	ITEM_USE_ERROR_TYPE_ERROR,
							'params'	=>	array(
								'id'	=>	$id,
								'remain'=>	$remain
							)
						);
					}
				}
			}
			else
			{
				$json = array(
					'code'		=>	ITEM_USE_ERROR_NOT_EXIST,
					'params'	=>	array(
						'id'	=>	$id
					)
				);
			}
		}
		else
		{
			$json = array(
				'code'		=>	ITEM_USE_ERROR_NO_PARAM,
				'params'	=>	array(
					'id'	=>	$id
				)
			);
		}

		echo $this->return_format->format($json);
	}

	public function sell()
	{
		header('Content-type: text/json');
		$this->load->model('utils/return_format');

		$id = $this->input->post('id');
		$count = intval($this->input->post('count'));

		if(!empty($id) && !empty($count))
		{
			$this->load->model('mitem');
			$key = array(
				'id'		=>	$id,
				'role_id'	=>	$this->currentRole->role['id']
			);
			$result = $this->mitem->read($key);
			if(!empty($result))
			{
				$result = $result[0];
				if($result['is_locked'] == '1')
				{
					$remain = $result['count'];
					$json = array(
						'code'		=>	EQUIPMENT_SELL_ERROR_LOCKED,
						'params'	=>	array(
							'id'	=>	$id,
							'count'	=>	$count,
							'remain'=>	$remain
						)
					);
				}
				else
				{

					if($result['count'] > $count)
					{
						$role_id = $this->currentRole->role['id'];
						$sql = "UPDATE `items` SET `count`=`count`-{$count} WHERE `id`={$id} AND `role_id`={$role_id}";
						$this->mitem->db()->query($sql);
						$remain = $result['count'] - $count;
					}
					elseif($result['count'] == $count)
					{
						$this->mitem->delete($key);
						$remain = 0;
					}
					else
					{
						$remain = $result['count'];
						$json = array(
							'code'		=>	ITEM_SELL_ERROR_NOT_ENOUGH,
							'params'	=>	array(
								'id'	=>	$id,
								'count'	=>	$count,
								'remain'=>	$remain
							)
						);
						echo $this->return_format->format($json);
						exit();
					}

					$price = $result['price'] * $count;
					$this->load->library('Gift');
					$this->_hook_gifts($this->currentRole->role);
					$parameter = array(
						'action'	=>	'sell',
						'price'		=>	$price
					);
					$this->gift->call_hook('after_billing_sell', $parameter);
					$price = $parameter['price'];

					$this->currentRole->role['gold'] += $price;
					$this->currentRole->save();

					$json = array(
						'code'		=>	ITEM_SELL_SUCCESS,
						'params'	=>	array(
							'id'	=>	$id,
							'name'	=>	$result['name'],
							'count'	=>	$count,
							'remain'=>	$remain,
							'price'	=>	$price
						)
					);
				}
			}
			else
			{
				$json = array(
					'code'		=>	ITEM_SELL_ERROR_NOT_EXIST,
					'params'	=>	array(
						'id'	=>	$id,
						'count'	=>	$count
					)
				);
			}
		}
		else
		{
			$json = array(
				'code'		=>	ITEM_SELL_ERROR_NO_PARAM,
				'params'	=>	array(
					'id'	=>	$id,
					'count'	=>	$count
				)
			);
		}

		echo $this->return_format->format($json);
	}

	public function sell_all()
	{
		$this->load->model('mitem');
		$key = array(
			'role_id'		=>	$this->currentRole->role['id'],
			'is_locked'		=>	0
		);
		$result = $this->mitem->read($key);
		$price = 0;
		foreach($result as $row)
		{
			$price += $row['count'] * $row['price'];
		}

		if($price > 0)
		{
			$this->load->library('Gift');
			$this->_hook_gifts($this->currentRole->role);
			$gift_param = array(
				'action'	=>	'sell',
				'price'		=>	$price
			);
			$this->gift->call_hook('after_billing_sell', $gift_param);
			$price = $gift_param['price'];

			$this->mitem->delete($key);

			$this->currentRole->role['gold'] += $price;
			$this->currentRole->save();

			redirect('role/item');
		}
		else
		{
			showMessage( MESSAGE_TYPE_ERROR, 'ITEM_SELL_ERROR_NO_SELL_ITEMS', '', 'role/item', true, 5 );
		}
	}

	public function lock($id)
	{
		header('Content-type: text/json');
		$this->load->model('utils/return_format');

		$id = $this->input->post('id');
		if(!empty($id))
		{
			$this->load->model('mitem');
			$key = array(
				'id'		=>	$id,
				'role_id'	=>	$this->currentRole->role['id']
			);
			$result = $this->mitem->read($key);
			if(!empty($result))
			{
				$result = $result[0];
				$parameter = array(
					'is_locked'		=>	1
				); 
				$this->mitem->update($key, $parameter);

				$json = array(
					'code'		=>	ITEM_LOCK_SUCCESS,
					'params'	=>	array(
						'id'	=>	$id,
						'name'	=>	$result['name']
					)
				);
			}
			else
			{
				$json = array(
					'code'		=>	ITEM_LOCK_ERROR_NOT_EXIST,
					'params'	=>	array(
						'id'	=>	$id
					)
				);
			}
		}
		else
		{
			$json = array(
				'code'		=>	ITEM_LOCK_ERROR_NO_PARAM
			);
		}

		echo $this->return_format->format($json);
	}

	public function unlock($id)
	{
		header('Content-type: text/json');
		$this->load->model('utils/return_format');

		$id = $this->input->post('id');
		if(!empty($id))
		{
			$this->load->model('mitem');
			$key = array(
				'id'		=>	$id,
				'role_id'	=>	$this->currentRole->role['id']
			);
			$result = $this->mitem->read($key);
			if(!empty($result))
			{
				$result = $result[0];
				$parameter = array(
					'is_locked'		=>	0
				); 
				$this->mitem->update($key, $parameter);

				$json = array(
					'code'		=>	ITEM_UNLOCK_SUCCESS,
					'params'	=>	array(
						'id'	=>	$id,
						'name'	=>	$result['name']
					)
				);
			}
			else
			{
				$json = array(
					'code'		=>	ITEM_UNLOCK_ERROR_NOT_EXIST,
					'params'	=>	array(
						'id'	=>	$id
					)
				);
			}
		}
		else
		{
			$json = array(
				'code'		=>	ITEM_UNLOCK_ERROR_NO_PARAM
			);
		}

		echo $this->return_format->format($json);
	}

	public function destroy($id, $count)
	{
		if(!empty($id) && !empty($count))
		{
			$this->load->model('mitem');
			$key = array(
				'id'		=>	$id,
				'role_id'	=>	$this->currentRole->role['id']
			);
			$result = $this->mitem->read($key);
			if(!empty($result))
			{
				$result = $result[0];
				if($result['is_locked'] == 1)
				{
					showMessage( MESSAGE_TYPE_ERROR, 'ITEM_DESTROY_ERROR_LOCKED', '', 'role/item', true, 5 );
				}
				else
				{
					if($result['count'] > $count)
					{
						$role_id = $this->currentRole->role['id'];
						$sql = "UPDATE `items` SET `count`=`count`-{$count} WHERE `id`={$id} AND `role_id`={$role_id}";
						$this->mitem->db()->query($sql);

						redirect('role/item');
					}
					elseif($result['count'] == $count)
					{
						$this->mitem->delete($key);

						redirect('role/item');
					}
					else
					{
						showMessage( MESSAGE_TYPE_ERROR, 'ITEM_DESTROY_ERROR_NOT_ENOUGH', '', 'role/item', true, 5 );
					}
				}
			}
			else
			{
				showMessage( MESSAGE_TYPE_ERROR, 'ITEM_DESTROY_ERROR_NOT_EXIST', '', 'role/item', true, 5 );
			}
		}
		else
		{
			showMessage( MESSAGE_TYPE_ERROR, 'ITEM_DESTROY_ERROR_NO_PARAM', '', 'role/item', true, 5 );
		}
	}

	private function _hook_gifts($role)
	{
		if(isset($role['gift']))
		{
			$gifts = json_decode($role['gift']);
			if(is_array($gifts))
			{
				$this->load->config('gifts.config');
				$giftConfig = $this->config->item('gifts_hook_action_name');
				foreach($gifts as $gift)
				{
					if(is_array($giftConfig[$gift]))
					{
						foreach($giftConfig[$gift] as $action)
						{
							$parameter = array(
								'action'	=>	$action,
								'gift_id'	=>	$gift
							);

							$this->gift->hook($parameter);
						}
					}
				}
			}
		}
	}
}

?>