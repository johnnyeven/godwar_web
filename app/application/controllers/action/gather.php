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
				$rand = rand(1, 10000) / 10000;
				if($rand <= $this->currentRole->role['gather_trigger'])
				{
					$map_id = intval($this->currentRole->role['map_id']);
                    $this->load->model('config/map_config');
					$parameter = array(
						'id'		=>	$map_id
					);
					$result = $this->map_config->read($parameter);
					if(!empty($result))
					{
						$result = $result[0];
						$this->load->helper('array');
						$id = intval(rate_random_element($result['gather']));
                        $this->load->model('config/item_config');
						$parameter = array(
							'id'	=>	$id
						);
						$item = $this->item_config->read($parameter);
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
					$this->load->config('base.config');
					$gather_rest_time = $this->config->item('base_gather_rest_time');
					$this->currentRole->role['gathertime'] = $time;
					$this->currentRole->role['next_gathertime'] = $time + $gather_rest_time;
					$this->currentRole->save();

					$json = array(
						'code'		=>	GATHER_NOTHING,
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