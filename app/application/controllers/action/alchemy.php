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

		$this->load->model('malchemy');
		$parameter = array(
			'role_id'	=>	$this->currentRole->role['id']
		);
		$result = $this->malchemy->read($parameter, $extension);

		$data = array(
			'result'	=>	$result,
			'role'		=>	$this->currentRole->role
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

						$this->load->library('Mongo_db');
						$parameter = array(
							'id'	=>	intval($id)
						);
						$mongo_result = $this->mongo_db->where($parameter)->get('item');
						$mongo_result = $mongo_result[0];
						$parameter = array(
							'id'	=>	$mongo_result['make_item_id']
						);
						$product_result = $this->mongo_db->where($parameter)->get('alchemy');
						$product_result = $product_result[0];

						$key['name'] = $result['name'];
						$key['materials'] = json_encode($product_result['materials']);
						$key['product_id'] = $mongo_result['make_item_id'];
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

		$id = $this->input->post('id');

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
					'id'	=>	$result['product_id']
				);
				$product = $this->mongo_db->where($param)->get('item');
				$product = $product[0];
				$parameter['product'] = array(
					'id'		=>	$product['id'],
					'name'		=>	$product['name'],
					'comment'	=>	$product['comment'],
					'type'		=>	$product['type']
				);

				$materials = json_decode($result['materials']);
				foreach($materials as $key => $value)
				{
					$param = array(
						'id'	=>	$value['id']
					);
					$m = $this->mongo_db->where($param)->get('item');
					$m = $m[0];
				}
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
}

?>