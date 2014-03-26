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
			'role'		=>	$this->currentRole->role
		);
		$this->load->model( 'utils/render' );
		$this->render->render( $this->pageName, $data );
	}

	public function cancel($id)
	{
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
				$equipment_id = $result['equipment_id'];

				$parameter = array(
					'status'	=>	3
				);
				$this->mmarket->update($id, $parameter);

				$this->load->model('mequipment');
				$parameter = array(
					'is_market'	=>	0
				);
				$this->mequipment->update($equipment_id, $parameter);

				redirect('action/market');
			}
			else
			{
				showMessage( MESSAGE_TYPE_ERROR, 'MARKET_ERROR_NOT_EXIST', '', 'action/market', true, 5 );
			}
		}
		else
		{
			showMessage( MESSAGE_TYPE_ERROR, 'MARKET_ERROR_NO_PARAM', '', 'action/market', true, 5 );
		}
	}

	public function sell($id)
	{
		if(!empty($id))
		{
			$this->load->model('mequipment');
			$parameter = array(
				'id'		=>	$id,
				'role_id'	=>	$this->currentRole->role['id']
			);
			$result = $this->mequipment->read($parameter);
			if(!empty($result))
			{
				$this->pageName = 'action/market_sell';

				$result = $result[0];
				if($result['is_equipped'] == '1')
				{
					showMessage( MESSAGE_TYPE_ERROR, 'EQUIPMENT_MARKET_ERROR_EQUIPPED', '', 'role/equipment', true, 5 );
				}
				elseif($result['is_locked'] == '1')
				{
					showMessage( MESSAGE_TYPE_ERROR, 'EQUIPMENT_MARKET_ERROR_LOCKED', '', 'role/equipment', true, 5 );
				}
				elseif($result['is_market'] == '1')
				{
					showMessage( MESSAGE_TYPE_ERROR, 'EQUIPMENT_MARKET_ERROR_IN_MARKET', '', 'role/equipment', true, 5 );
				}
				elseif($result['is_destroyed'] == '1')
				{
					showMessage( MESSAGE_TYPE_ERROR, 'EQUIPMENT_MARKET_ERROR_DESTROYED', '', 'role/equipment', true, 5 );
				}
				else
				{
					$data = array(
						'equipment'	=>	$result
					);

					$this->load->model( 'utils/render' );
					$this->render->render( $this->pageName, $data );
				}
			}
			else
			{
				showMessage( MESSAGE_TYPE_ERROR, 'EQUIPMENT_ERROR_NOT_EXIST', '', 'role/equipment', true, 5 );
			}
		}
		else
		{
			showMessage( MESSAGE_TYPE_ERROR, 'EQUIPMENT_ERROR_NO_PARAM', '', 'role/equipment', true, 5 );
		}
	}

	public function sell_submit()
	{
		$id = $this->input->post('id');
		$price = $this->input->post('price');
		$endtime = $this->input->post('endtime');

		if(!empty($id))
		{
			$this->load->model('mequipment');
			$parameter = array(
				'id'		=>	$id,
				'role_id'	=>	$this->currentRole->role['id']
			);
			$result = $this->mequipment->read($parameter);
			if(!empty($result))
			{
				$result = $result[0];
				if($result['is_equipped'] == '1')
				{
					showMessage( MESSAGE_TYPE_ERROR, 'EQUIPMENT_MARKET_ERROR_EQUIPPED', '', 'role/equipment', true, 5 );
				}
				elseif($result['is_locked'] == '1')
				{
					showMessage( MESSAGE_TYPE_ERROR, 'EQUIPMENT_MARKET_ERROR_LOCKED', '', 'role/equipment', true, 5 );
				}
				elseif($result['is_market'] == '1')
				{
					showMessage( MESSAGE_TYPE_ERROR, 'EQUIPMENT_MARKET_ERROR_IN_MARKET', '', 'role/equipment', true, 5 );
				}
				elseif($result['is_destroyed'] == '1')
				{
					showMessage( MESSAGE_TYPE_ERROR, 'EQUIPMENT_MARKET_ERROR_DESTROYED', '', 'role/equipment', true, 5 );
				}
				else
				{
					if(empty($price))
					{
						$price = $result['price'];
					}

					$time = time();
					$parameter = array(
						'role_id'				=>	$this->currentRole->role['id'],
						'equipment_id'			=>	$result['id'],
						'equipment_name'		=>	$result['name'],
						'equipment_position'	=>	$result['position'],
						'equipment_level'		=>	$result['level'],
						'equipment_grade'		=>	$result['grade'],
						'equipment_job'			=>	$result['job'],
						'atk_base'				=>	$result['atk_base'],
						'def_base'				=>	$result['def_base'],
						'mdef_base'				=>	$result['mdef_base'],
						'health_max_base'		=>	$result['health_max_base'],
						'hit_base'				=>	$result['hit_base'],
						'flee_base'				=>	$result['flee_base'],
						'magic_words'			=>	$result['magic_words'],
						'price'					=>	$price,
						'starttime'				=>	$time,
						'endtime'				=>	$time + $endtime * 86400,
						'status'				=>	1
					);
					$this->load->model('mmarket');
					$this->mmarket->create($parameter);

					$parameter = array(
						'is_market'		=>	1
					);
					$this->mequipment->update($id, $parameter);

					showMessage( MESSAGE_TYPE_SUCCESS, 'MARKET_SELL_SUBMIT', '', 'action/market', true, 5 );
				}
			}
			else
			{
				showMessage( MESSAGE_TYPE_ERROR, 'EQUIPMENT_ERROR_NOT_EXIST', '', 'role/equipment', true, 5 );
			}
		}
		else
		{
			showMessage( MESSAGE_TYPE_ERROR, 'EQUIPMENT_ERROR_NO_PARAM', '', 'role/equipment', true, 5 );
		}
	}

	public function buy($id)
	{
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
				if($result['status'] == '2')
				{
					showMessage( MESSAGE_TYPE_ERROR, 'MARKET_ERROR_ALREADY_COMPLETED', '', 'action/market', true, 5 );
				}
				elseif($result['status'] == '3')
				{
					showMessage( MESSAGE_TYPE_ERROR, 'MARKET_ERROR_CANCELED', '', 'action/market', true, 5 );
				}
				elseif($result['role_id'] == $this->currentRole->role['id'])
				{
					showMessage( MESSAGE_TYPE_ERROR, 'MARKET_ERROR_SELF_ORDER', '', 'action/market', true, 5 );
				}
				else
				{
					$this->pageName = 'action/market_buy';
					$data = array(
						'order'	=>	$result
					);

					$this->load->model( 'utils/render' );
					$this->render->render( $this->pageName, $data );
				}
			}
			else
			{
				showMessage( MESSAGE_TYPE_ERROR, 'MARKET_ERROR_NOT_EXIST', '', 'action/market', true, 5 );
			}
		}
		else
		{
			showMessage( MESSAGE_TYPE_ERROR, 'MARKET_ERROR_NO_PARAM', '', 'action/market', true, 5 );
		}
	}

	public function buy_submit()
	{
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
				if($result['status'] == '2')
				{
					showMessage( MESSAGE_TYPE_ERROR, 'MARKET_ERROR_ALREADY_COMPLETED', '', 'action/market', true, 5 );
				}
				elseif($result['status'] == '3')
				{
					showMessage( MESSAGE_TYPE_ERROR, 'MARKET_ERROR_CANCELED', '', 'action/market', true, 5 );
				}
				elseif($result['role_id'] == $this->currentRole->role['id'])
				{
					showMessage( MESSAGE_TYPE_ERROR, 'MARKET_ERROR_SELF_ORDER', '', 'action/market', true, 5 );
				}
				elseif($result['price'] > $this->currentRole->role['gold'])
				{
					showMessage( MESSAGE_TYPE_ERROR, 'MARKET_ERROR_NOT_ENOUGH_GOLD', '', 'action/market', true, 5 );
				}
				else
				{
					$parameter = array(
						'status'	=>	2
					);
					$this->mmarket->update($id, $parameter);

					$this->load->model('mequipment');
					$parameter = array(
						'role_id'		=>	$this->currentRole->role['id'],
						'is_market'		=>	0
					);
					$this->mequipment->update($result['equipment_id'], $parameter);

					$this->load->model('role');
					$db = $this->role->db();
					$db->set('gold', 'gold + ' . $result['price'], FALSE);
					$db->where('id', $result['role_id']);
					$db->update('roles');

					$this->currentRole->role['gold'] -= $result['price'];
					$this->currentRole->save();

					redirect('action/market');
				}
			}
			else
			{
				showMessage( MESSAGE_TYPE_ERROR, 'MARKET_ERROR_NOT_EXIST', '', 'action/market', true, 5 );
			}
		}
		else
		{
			showMessage( MESSAGE_TYPE_ERROR, 'MARKET_ERROR_NO_PARAM', '', 'action/market', true, 5 );
		}
	}
}

?>