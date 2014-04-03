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
		$result = $this->malchemy->read($parameter);

		$this->load->model('mitem');
		$parameter = array(
			'role_id'	=>	$this->currentRole->role['id']
		);
		$item_result = $this->mitem->read($parameter);

		$data = array(
			'result'	=>	$result,
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
}

?>