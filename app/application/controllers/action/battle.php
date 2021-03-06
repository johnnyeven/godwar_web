<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Battle extends CI_Controller {
	private $pageName = 'action/battle';
	private $user = null;
	private $currentRole = null;

	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'utils/check_user', 'check' );
		$this->user = $this->check->validate ();
		$this->currentRole = $this->check->check_role ();
		$this->currentRole->check_role_status();

		$this->load->library('Gift');
	}

	public function index() {
		$this->currentRole->role['current_action'] = 1;
		$this->currentRole->save();

		$parameter = array (
				'role' => $this->currentRole->role
		);
		$this->load->model ( 'utils/render' );
		$this->render->render ( $this->pageName, $parameter );
	}

	public function request_battle() {
		header('Content-type:text/json');
		$mem_usage_pre = memory_get_usage();

		if($this->currentRole->role['current_action'] == '1')
		{
			if($this->currentRole->role ['health'] == '0')
			{
				$battleResult = array (
						'err' => ERROR_ROLE_DEAD,
						'attacker' => $this->currentRole->role,
						'timestamp' => $time,
						'next_battletime' => $this->currentRole->role ['next_battletime'] 
				);
			}
			else
			{
				$time = time ();
				if ($this->currentRole->role ['next_battletime'] > $time)
				{
					$battleResult = array (
							'err' => ERROR_BATTLE_TIME_NOT_TO,
							'attacker' => $this->currentRole->role,
							'timestamp' => $time,
							'next_battletime' => $this->currentRole->role ['next_battletime'] 
					);
					// if ($this->currentRole->role ['health'] != $this->currentRole->role ['health_max']) {
					// 	$this->load->model ( 'role' );
					// 	$parameter = array (
					// 			'health' => $this->currentRole->role ['health'],
					// 			'battletime' => $time 
					// 	);
					// 	$this->role->update ( $this->currentRole->role ['id'], $parameter );
					// }
				}
				else
				{
					$this->load->config('base.config');

					$recover_health = $this->config->item('base_recover_health_rate');
					$battle_rest_time = $this->config->item('base_battle_rest_time');

					$this->_hook_gifts($this->currentRole->role);
					
					$pass = $time - $this->currentRole->role ['battletime'];
					$parameter = array(
						'pass'		=>	$pass,
						'recover'	=>	$recover_health
					);
					
					//Gift hook: 血量恢复
					$this->gift->call_hook('before_recover_health', $parameter);
					$recover = $parameter['pass'] * $parameter['recover'];
					$this->currentRole->role ['health'] += $recover;
					if ($this->currentRole->role ['health'] > $this->currentRole->role ['health_max'])
					{
						$this->currentRole->role ['health'] = $this->currentRole->role ['health_max'];
					}

					$monster = $this->getMonsterByNearestLevel ();
					$monster ['health_max'] = $monster ['health'];

					$dex = 10;
					$k = 1.13;
					$m = 100;
					$d = 2.3 * $dex / (10 + 2.3 * $dex);
					
					if ($monster != null) {
						// unset ( $role ['account_id'] );
						// unset ( $role ['race'] );
						// unset ( $role ['job'] );
						// unset ( $role ['createtime'] );
						// unset ( $role ['lasttime'] );
						// unset ( $role ['map_id'] );
						// unset ( $role ['next_battletime'] );
						
						unset ( $monster ['comment'] );

						//Gift hook: 战斗前的hook
						$this->gift->call_hook('before_battle', $this->currentRole->role);

						$this->currentRole->role ['atk_min'] = $this->currentRole->role ['atk'] * $d;
						$this->currentRole->role ['def_percent'] = $k * $this->currentRole->role ['def'] / ($m + $k * $this->currentRole->role ['def']);
						$this->currentRole->role ['mdef_percent'] = $k * $this->currentRole->role ['mdef'] / ($m + $k * $this->currentRole->role ['mdef']);
						$monster ['atk_min'] = $monster ['atk'] * $d;
						$monster ['def_percent'] = $k * $monster ['def'] / ($m + $k * $monster ['def']);
						$monster ['mdef_percent'] = $k * $monster ['mdef'] / ($m + $k * $monster ['mdef']);
						
						$attacker = $this->currentRole->role;
						$defender = $monster;
						$win = false;
						
						$battleResult = array (
							'monster'	=>	$monster,
							'rounds'	=>	array()
						);
						$round = 1;
						
						$this->load->model('skills/skill_default');
						$this->load->helper('array');
						while ( $attacker ['health'] > 0 && $defender ['health'] > 0 ) {
							$item = array ();
							$skillTrigger = floatval ( $attacker ['skill_trigger'] );
							$skills = $attacker['skill'];
							$rand = rand ( 0, 100000 ) / 100000;
							if (!empty($skills) && $rand <= $skillTrigger) {
								if(!empty($attacker['main_skill']))
								{
									$rand = rand ( 0, 100 );
									if($rand <= 50)
									{
										$skillId = 'skill_' . $attacker['main_skill'];
									}
									else
									{
										$skillId = 'skill_' . random_element($skills);
									}
								}
								else
								{
									$skillId = 'skill_' . random_element($skills);
								}
								//TODO 还没有写技能系统
								// $skillId = 'skill_default';
								$this->load->model ( "skills/{$skillId}" );
							} else {
								$skillId = 'skill_default';
							}
							$damage = $this->$skillId->execute ( $attacker, $defender );
							$item ['damage'] = array();

							if(!empty($damage))
							{
								if(isset($damage ['damage']))
								{
									$defender ['health'] -= $damage ['damage'];
									$defender ['health'] = $defender ['health'] < 0 ? 0 : $defender ['health'];
								}
								$item ['round'] = $round;
								array_push($item['damage'], $damage);
							}

							if(isset($defender['status']) && is_array($defender['status']))
							{
								foreach($defender['status'] as $key => $value)
								{
									$status_model = 'status_' . $key;
									$this->load->model('skills/' . $status_model);
									$statu = $this->$status_model->execute($defender, $value[1]);

									if(!empty($statu))
									{
										array_push($item ['damage'], $statu);
									}

									$defender['status'][$key][0]--;
									if($defender['status'][$key][0] <= 0)
									{
										$this->$status_model->destroy($defender);
										unset($defender['status'][$key]);
									}
								}
								if(count($defender['status']) == 0)
								{
									unset($defender['status']);
								}
							}
							$defender ['atk_min'] = $defender ['atk'] * $d;
							$defender ['def_percent'] = $k * $defender ['def'] / ($m + $k * $defender ['def']);
							$defender ['mdef_percent'] = $k * $defender ['mdef'] / ($m + $k * $defender ['mdef']);

							$item ['attacker'] = $attacker;
							$item ['defender'] = $defender;
							array_push ( $battleResult['rounds'], $item );
							
							if ($defender ['health'] <= 0) {
								if ($defender ['name'] == $this->currentRole->role ['name']) {
									$monster = $attacker;
									$this->currentRole->role = $defender;
								} else {
									$win = true;
									$monster = $defender;
									$this->currentRole->role = $attacker;
								}

								if(isset($this->currentRole->role['status']) && is_array($this->currentRole->role['status']))
								{
									foreach($this->currentRole->role['status'] as $key => $value)
									{
										$status_model = 'status_' . $key;
										$this->load->model('skills/' . $status_model);
										$this->$status_model->destroy($this->currentRole->role);
										unset($this->currentRole->role['status'][$key]);
									}
								}
								break;
							}
							
							$exchange = $attacker;
							$attacker = $defender;
							$defender = $exchange;
							$round ++;
						}
						
						if ($win) {
							//Gift hook: 战斗结算前
							$this->gift->call_hook('before_settle_battle', $monster);
							$settle = $this->_settle_battle($monster, $this->currentRole->role);
							//Gift hook: 战斗结算后
							$this->gift->call_hook('after_settle_battle', $settle);

							$battleResult['result'] = 1;
							$battleResult['settle'] = $settle;

							$restHealthPercentage = 1;
							$judgmentHealth = $this->currentRole->role ['health_max'] * $restHealthPercentage;
							if ($this->currentRole->role ['health'] <= $judgmentHealth) {
								$restTime = ceil ( ($judgmentHealth - $this->currentRole->role ['health']) / $recover_health );
							}
							$this->currentRole->role ['exp'] += $settle ['exp'];
							$this->currentRole->role ['gold'] += $settle ['gold'];
							if ($this->currentRole->role ['exp'] >= $this->currentRole->role ['nextexp']) {
								// 升级
								//Gift hook: 升级前
								$this->gift->call_hook('before_level_up', $this->currentRole->role);

								$this->currentRole->role_level_up();

								//Gift hook: 升级后
								$this->gift->call_hook('after_level_up', $this->currentRole->role);
							}

							//Gift hook: 战斗胜利后的hook
							$this->gift->call_hook('after_battle_win', $this->currentRole->role);
						} else {
							$battleResult['result'] = 0;

							//Gift hook: 战斗失败后的hook
							$this->gift->call_hook('after_battle_fail', $this->currentRole->role);
						}
						
						$this->currentRole->role ['battletime'] = $time;
						if($restTime < $battle_rest_time)
						{
							$restTime = $battle_rest_time;
						}
						$this->currentRole->role ['next_battletime'] = $time + $restTime;
						
						$battleResult ['timestamp'] = $time;
						$battleResult ['next_battletime'] = $this->currentRole->role ['next_battletime'];

						$this->currentRole->save();

						$battleResult ['final'] = $this->currentRole->role;
					}
				}
			}
		}
		else
		{
			$battleResult = array (
				'err' => BATTLE_ERROR_CONFLICT
			);
		}

		$mem_usage_next = memory_get_usage();
		$mem_usage = $mem_usage_next - $mem_usage_pre;
		$battleResult ['mem'] = $mem_usage;
		echo json_encode ( $battleResult );
	}

	private function getMonsterByNearestLevel()
	{
		$mapId = intval ( $this->currentRole->role ['map_id'] );
		$level = intval ( $this->currentRole->role ['level'] );
		
		$this->load->library ( 'Mongo_db' );
		$parameter = array (
				'id' => $mapId 
		);
		$result = $this->mongo_db->where ( $parameter )->get ( 'map' );
		$result = $result [0];
		
		if (! empty ( $result )) {
			$resultMonster = $this->mongo_db->where_in ( 'id', $result ['monster'] )->order_by ( array (
					'level' => 'desc' 
			) )->get ( 'monster' );
			if (! empty ( $resultMonster )) {
				$rand = rand(0, 100);
				if($rand <= 50)
				{
					$currentLevel = 0;
					$monsters = array ();
					
					$count = count ( $resultMonster );
					if ($resultMonster [$count - 1] ['level'] > $level) {
						$currentLevel = $resultMonster [$count - 1] ['level'];
						array_push ( $monsters, $resultMonster [$count - 1] );
						for($i = $count - 2; $i >= 0; $i --) {
							if ($resultMonster [$i] ['level'] != $currentLevel) {
								break;
							}
							array_push ( $monsters, $resultMonster [$i] );
						}
					} else {
						foreach ( $resultMonster as $value ) {
							if ($currentLevel == 0 && $value ['level'] <= $level) {
								$currentLevel = $value ['level'];
								array_push ( $monsters, $value );
								continue;
							}
							if ($value ['level'] == $currentLevel) {
								array_push ( $monsters, $value );
							}
						}
					}
					$count = count ( $monsters );
					$monster = $monsters [rand ( 0, $count - 1 )];
					
					return $monster;
				}
				else
				{
					return $resultMonster[rand(0, count($resultMonster) - 1)];
				}
			} else {
				return null;
			}
		} else {
			return null;
		}
	}

	private function _hook_gifts($role)
	{
		if(isset($role['gift']))
		{
			$gifts = $role['gift'];
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
								'type'		=>	'gift',
								'gift_id'	=>	$gift
							);

							$this->gift->hook($parameter);
						}
					}
				}
			}
		}

		if(!empty($role['append_status']))
		{
			foreach($role['append_status'] as $key => $value)
			{
				if($value['type'] == 'hook')
				{
					$parameter = array(
						'action'	=>	$value['action'],
						'type'		=>	'status',
						'gift_id'	=>	$key
					);

					$this->gift->hook($parameter);
				}
			}
		}
	}

	private function _role_level_up(& $role, $raceResult, $expResult)
	{
		$role ['health_base'] += $raceResult ['health_inc'];
		$role ['health_max'] = $role ['health_base'];
		$role ['health'] = $role ['health_max'];
		$role ['atk_base'] += $raceResult ['atk_inc'];
		$role ['atk'] = $role ['atk_base'];
		$role ['def_base'] += $raceResult ['def_inc'];
		$role ['def'] = $role ['def_base'];
		$role ['mdef_base'] += $raceResult ['mdef_inc'];
		$role ['mdef'] = $role ['atk_base'];
		$role ['hit_base'] += $raceResult ['hit_inc'];
		$role ['hit'] = $role ['hit_base'];
		$role ['flee_base'] += $raceResult ['flee_inc'];
		$role ['flee'] = $role ['flee_base'];
	}

	private function _settle_battle($monster, $role)
	{
		$settle = array(
			'exp'		=>	intval($monster['exp'] * (1 + $role['exp_inc'])),
			'gold'		=>	intval($monster['gold'] * (1 + $role['gold_inc'])),
			'drop'		=>	array()
		);

		$rate_inc = $this->config->item('base_equipment_drop_rate_inc');
		$rand = rand(0, 100000) / 100000;
		// $rand = 0;
		if($rand <= ($monster['equipment_drop']) * $rate_inc)
		{
			if(!empty($monster['equipments']) && is_array($monster['equipments']))
			{
				$id = rate_random_element($monster['equipments']);
				if(!empty($id))
				{
					$parameter = array(
						'id'	=>	intval($id)
					);
					$result = $this->mongo_db->where($parameter)->get('equipment');
					if(!empty($result))
					{
						$result = $result[0];
						$result['grade'] = 0;

						$rand = rand(0, 100000) / 100000;
						// $rand = 0.01;
						if($rand <= $monster['gold_drop'])
						{
							$result['grade'] = 4;
							$result = $this->_generate_magic_word($monster, $role, $result);
						}
						else if($rand <= $monster['purple_drop']) //两个魔法前缀和一个魔法后缀、一个魔法前缀和两个魔法后缀
						{
							$result['grade'] = 3;
							$result = $this->_generate_magic_word($monster, $role, $result);
						}
						else if($rand <= $monster['green_drop']) //一个魔法前缀和一个魔法后缀
						{
							$result['grade'] = 2;
							$result = $this->_generate_magic_word($monster, $role, $result);
						}
						else if($rand <= $monster['blue_drop']) //一个魔法前缀或一个魔法后缀
						{
							$result['grade'] = 1;
							$result = $this->_generate_magic_word($monster, $role, $result);
						}
						$result['type'] = 1;

						$this->load->model('mequipment');
						$parameter = array(
							'role_id'			=>	intval($role['id']),
							'original_id'		=>	intval($result['id']),
							'name'				=>	$result['name'],
							'position'			=>	intval($result['position']),
							'level'				=>	intval($result['level']),
							'grade'				=>	intval($result['grade']),
							'job'				=>	json_encode($result['job']),
							'atk_base'			=>	intval($result['atk']),
							'def_base'			=>	intval($result['def']),
							'mdef_base'			=>	intval($result['mdef']),
							'health_max_base'	=>	intval($result['health']),
							'hit_base'			=>	intval($result['hit']),
							'flee_base'			=>	intval($result['flee']),
							'magic_words'		=>	empty($result['magic_word']) ? '' : json_encode($result['magic_word']),
							'price'				=>	intval($result['price'])
						);
						$this->mequipment->create($parameter);
						array_push($settle['drop'], $result);
					}
				}
			}
		}

		$rate_inc = $this->config->item('base_item_drop_rate_inc');
		$rand = rand(0, 100000) / 100000;
		if($rand <= ($monster['item_drop']) * $rate_inc)
		{
			if(!empty($monster['items']) && is_array($monster['items']))
			{
				$id = rate_random_element($monster['items']);
				if(!empty($id))
				{
					$parameter = array(
						'id'	=>	intval($id)
					);
					$result = $this->mongo_db->where($parameter)->get('item');
					if(!empty($result))
					{
						$result = $result[0];

						$this->load->model('mitem');
						$parameter = array(
							'id'		=>	$id,
							'role_id'	=>	$role['id']
						);
						$tmp = $this->mitem->read($parameter);
						if(!empty($tmp))
						{
							$sql = "UPDATE `items` SET `count`=`count`+1 WHERE `id`={$id} AND `role_id`={$role['id']}";
							$this->mitem->db()->query($sql);
						}
						else
						{
							$parameter['name'] = $result['name'];
							$parameter['type'] = $result['type'];
							$parameter['count'] = 1;
							$parameter['price'] = $result['price'];
							$parameter['comment'] = $result['comment'];
							$parameter['remain_time'] = $result['remain_time'];
							$parameter['atk_inc'] = $result['atk_inc'];
							$parameter['atk_inc_unit'] = $result['atk_inc_unit'];
							$parameter['def_inc'] = $result['def_inc'];
							$parameter['def_inc_unit'] = $result['def_inc_unit'];
							$parameter['mdef_inc'] = $result['mdef_inc'];
							$parameter['mdef_inc_unit'] = $result['mdef_inc_unit'];
							$parameter['health_max_inc'] = $result['health_max_inc'];
							$parameter['health_max_inc_unit'] = $result['health_max_inc_unit'];
							$parameter['hit_inc'] = $result['hit_inc'];
							$parameter['hit_inc_unit'] = $result['hit_inc_unit'];
							$parameter['crit_inc'] = $result['crit_inc'];
							$parameter['crit_inc_unit'] = $result['crit_inc_unit'];
							$parameter['flee_inc'] = $result['flee_inc'];
							$parameter['flee_inc_unit'] = $result['flee_inc_unit'];
							$parameter['exp_inc'] = $result['exp_inc'];
							$parameter['exp_inc_unit'] = $result['exp_inc_unit'];
							$parameter['gold_inc'] = $result['gold_inc'];
							$parameter['gold_inc_unit'] = $result['gold_inc_unit'];
							$parameter['vitality_inc'] = $result['vitality_inc'];
							$parameter['vitality_inc_unit'] = $result['vitality_inc_unit'];
							$parameter['make_item_id'] = $result['make_item_id'];
							$this->mitem->create($parameter);
						}
						array_push($settle['drop'], $result);
					}
				}
			}
		}

		return $settle;
	}

	private function _generate_magic_word($monster, $role, $item)
	{
		$pre_words_groups = array(1,2,3,4,5,6,7);
		$next_words_groups = array(1,2,3,4,5);

		$magic_word = array();
		$type = rand(1, 2);
		$pre_words = '';
		$next_words = '';
		$not_in_id = array();

		for($i = 0; $i<$item['grade']; $i++)
		{
			if($type == 1)
			{
				$group = random_element($pre_words_groups);
			}
			else
			{
				$group = random_element($next_words_groups);
			}
			$parameter = array(
				'type'	=>	$type,
				'group'	=>	$group
			);
			$word = $this->mongo_db->where($parameter)->where_lt('level', $monster['level'] + 2)->where_not_in('id', $not_in_id)->order_by("level", "desc")->limit(1)->get('magic_word');
			if(empty($word))
			{
				$word = $this->mongo_db->where($parameter)->where_not_in('id', $not_in_id)->order_by("level", "desc")->limit(1)->get('magic_word');
			}
			$word = $word[0];
			foreach($word['property'] as $key => $value)
			{
				if(empty($value))
				{
					unset($word['property'][$key]);
				}
			}

			if($type == 1)
			{
				$pre_words .= $word['name'];
			}
			else
			{
				$next_words .= $word['name'];
			}
			array_push($not_in_id, $word['id']);
			array_push($magic_word, $word);

			$item['price'] += $word['level'] * 100;

			if($type > 1)
			{
				$type = 1;
			}
			else
			{
				$type++;
			}
		}

		if(!empty($pre_words))
		{
			$pre_words .= '的';
		}
		if(!empty($next_words))
		{
			$next_words .= '之';
		}
		$item['name'] = $pre_words . $next_words . $item['name'];

		if(!empty($magic_word))
		{
			$item['magic_word'] = $magic_word;
		}

		return $item;
	}
}

?>