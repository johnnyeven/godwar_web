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

		$this->load->library('Gift');
	}

	public function index() {
		$parameter = array (
				'role' => $this->currentRole 
		);
		$this->load->model ( 'utils/render' );
		$this->render->render ( $this->pageName, $parameter );
	}

	public function request_battle() {
		header('Content-type:text/json');

		if($this->currentRole ['health'] == '0')
		{
			$battleResult = array (
					'err' => ERROR_ROLE_DEAD,
					'attacker' => $this->currentRole,
					'timestamp' => $time,
					'next_battletime' => $this->currentRole ['next_battletime'] 
			);
		}
		else
		{
			$this->load->config('base.config');

			$recover_health = $this->config->item('base_recover_health_rate');
			$battle_rest_time = $this->config->item('base_battle_rest_time');

			$time = time ();
			$pass = $time - $this->currentRole ['battletime'];
			$recover = $pass * $recover_health;
			$this->currentRole ['health'] += $recover;
			if ($this->currentRole ['health'] > $this->currentRole ['health_max']) {
				$this->currentRole ['health'] = $this->currentRole ['health_max'];
			}
			if ($this->currentRole ['next_battletime'] > $time) {
				$battleResult = array (
						'err' => ERROR_BATTLE_TIME_NOT_TO,
						'attacker' => $this->currentRole,
						'timestamp' => $time,
						'next_battletime' => $this->currentRole ['next_battletime'] 
				);
				if ($this->currentRole ['health'] != $this->currentRole ['health_max']) {
					$this->load->model ( 'role' );
					$parameter = array (
							'health' => $this->currentRole ['health'],
							'battletime' => $time 
					);
					$this->role->update ( $this->currentRole ['id'], $parameter );
				}
			} else {
				$monster = $this->getMonsterByNearestLevel ();
				$monster ['health_max'] = $monster ['health'];
				$role = $this->currentRole;
				$this->_hook_gifts($role);

				$dex = 10;
				$k = 1.13;
				$m = 100;
				$d = 2.3 * $dex / (10 + 2.3 * $dex);
				
				if ($monster != null) {
					unset ( $role ['account_id'] );
					unset ( $role ['race'] );
					unset ( $role ['job'] );
					unset ( $role ['createtime'] );
					unset ( $role ['lasttime'] );
					unset ( $role ['map_id'] );
					unset ( $role ['next_battletime'] );
					$role ['skill'] = json_decode($role['skill'], TRUE);
					
					unset ( $monster ['comment'] );

					//Gift hook: 战斗前的hook
					$this->gift->call_hook('before_battle', $role);

					$role ['atk_min'] = $role ['atk'] * $d;
					$role ['def_percent'] = $k * $role ['def'] / ($m + $k * $role ['def']);
					$role ['mdef_percent'] = $k * $role ['mdef'] / ($m + $k * $role ['mdef']);
					$monster ['atk_min'] = $monster ['atk'] * $d;
					$monster ['def_percent'] = $k * $monster ['def'] / ($m + $k * $monster ['def']);
					$monster ['mdef_percent'] = $k * $monster ['mdef'] / ($m + $k * $monster ['mdef']);
					
					$attacker = $role;
					$defender = $monster;
					$win = false;
					
					$battleResult = array (
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
							if ($defender ['name'] == $role ['name']) {
								$monster = $attacker;
								$role = $defender;
							} else {
								$win = true;
								$monster = $defender;
								$role = $attacker;
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
						$settle = $this->_settle_battle($monster, $role);
						//Gift hook: 战斗结算后
						$this->gift->call_hook('after_settle_battle', $settle);

						$restHealthPercentage = .7;
						$judgmentHealth = $role ['health_max'] * $restHealthPercentage;
						if ($role ['health'] <= $judgmentHealth) {
							$restTime = ceil ( ($judgmentHealth - $role ['health']) / $recover_health );
						}
						$role ['exp'] += $monster ['exp'];
						if ($role ['exp'] > $role ['nextexp']) {
							// TODO 升级
							++ $role ['level'];
							$param = array (
									'id' => $this->currentRole ['race'] 
							);
							$raceResult = $this->mongo_db->where ( $param )->get ( 'race' );
							$raceResult = $raceResult [0];
							
							$param = array (
									'level' => intval ( $role ['level'] ) 
							);
							$expResult = $this->mongo_db->where ( $param )->get ( 'exp' );
							$expResult = $expResult [0];
							if (! empty ( $raceResult ) && ! empty ( $expResult ))
							{
								//Gift hook: 升级前
								$this->gift->call_hook('before_level_up', $role);

								$this->_role_level_up($role, $raceResult, $expResult);

								//Gift hook: 升级后
								$this->gift->call_hook('after_level_up', $role);

								$role['exp'] = 0;
								$role['nextexp'] = $expResult ['nextexp'];
							} else {
								$battleResult ['err'] = 2;
							}
						}

						//Gift hook: 战斗胜利后的hook
						$this->gift->call_hook('after_battle_win', $role);
					} else {
						//Gift hook: 战斗失败后的hook
						$this->gift->call_hook('after_battle_fail', $role);
					}

					$parameter = array (
							'level' => $role ['level'],
							'exp' => $role['exp'],
							'nextexp' => $role ['nextexp'],
							'health_base' => $role['health_base'],
							'health_max' => $role ['health_max'],
							'atk_base' => $role['atk_base'],
							'atk' => $role ['atk'],
							'def_base' => $role['def_base'],
							'def' => $role ['def'],
							'mdef_base' => $role['mdef_base'],
							'mdef' => $role ['mdef'],
							'hit_base' => $role['hit_base'],
							'hit' => $role ['hit'],
							'flee_base' => $role['flee_base'],
							'flee' => $role ['flee']
					);
					
					$parameter ['health'] = $role ['health'];
					$parameter ['battletime'] = $time;
					if($restTime < $battle_rest_time)
					{
						$restTime = $battle_rest_time;
					}
					$parameter ['next_battletime'] = $time + $restTime;
					
					$battleResult ['timestamp'] = $time;
					$battleResult ['next_battletime'] = $parameter ['next_battletime'];

					$this->load->model ( 'role' );
					$this->role->update ( $this->currentRole ['id'], $parameter );
				}
			}
		}

		echo json_encode ( $battleResult );
	}

	private function getMonsterByNearestLevel()
	{
		$mapId = intval ( $this->currentRole ['map_id'] );
		$level = intval ( $this->currentRole ['role_level'] );
		
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
			'exp'		=>	$monster['exp'],
			'gold'		=>	$monster['gold'],
			'drop'		=>	array()
		);

		// $rand = rand(0, 100000) / 100000;
		$rand = 0;
		if($rand <= $monster['equipment_drop'])
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

						// $rand = rand(0, 100000) / 100000;
						$rand = 0.0001;
						if($rand <= $monster['gold_drop'])
						{
							$result['grade'] = 4;
						}
						else if($rand <= $monster['purple_drop']) //两个魔法前缀和一个魔法后缀、一个魔法前缀和两个魔法后缀
						{
							$result['grade'] = 3;
						}
						else if($rand <= $monster['green_drop']) //一个魔法前缀和一个魔法后缀
						{
							$result['grade'] = 2;
						}
						else if($rand <= $monster['blue_drop']) //一个魔法前缀或一个魔法后缀
						{
							$result['grade'] = 1;
						}

						$result = $this->_generate_magic_word($monster, $role, $result);
						$result['type'] = 1;
						array_push($settle['drop'], $result);
					}
				}
			}
		}

		$rand = rand(0, 100000) / 100000;
		if($rand <= $monster['item_drop'])
		{

		}

		return $parameter;
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

			if($type > 1)
			{
				$type = 1;
			}
			else
			{
				$type++;
			}
		}

		$item['name'] = $pre_words . '的' . $next_words . '之' . $item['name'];

		if(!empty($magic_word))
		{
			$item['magic_word'] = $magic_word;
		}

		var_dump($item);
		exit();

		return $item;
	}
}

?>