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
				// $role ['health'] = $role ['health_max'];
				$dex = 10;
				$k = 1.13;
				$m = 100;
				
				if ($monster != null) {
					unset ( $role ['account_id'] );
					unset ( $role ['race'] );
					unset ( $role ['job'] );
					unset ( $role ['createtime'] );
					unset ( $role ['lasttime'] );
					unset ( $role ['map_id'] );
					unset ( $role ['next_battletime'] );
					$role ['skill'] = json_decode($role['skill'], TRUE);
					
					unset ( $monster ['level'] );
					unset ( $monster ['comment'] );
					
					$d = 2.3 * $dex / (10 + 2.3 * $dex);
					$role ['atk_min'] = $role ['atk'] * $d;
					$role ['def_percent'] = $k * $role ['def'] / ($m + $k * $role ['def']);
					$monster ['atk_min'] = $monster ['atk'] * $d;
					$monster ['def_percent'] = $k * $monster ['def'] / ($m + $k * $monster ['def']);
					
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
						if ($rand <= $skillTrigger) {
							$skillId = 'skill_' . rate_random_element($skills);
							$this->load->model ( "skills/{$skillId}" );
						} else {
							$skillId = 'skill_default';
						}
						$damage = $this->$skillId->execute ( $attacker, $defender );

						$defender ['health'] -= $damage ['damage'];
						$defender ['health'] = $defender ['health'] < 0 ? 0 : $defender ['health'];
						$item ['round'] = $round;
						$item ['damage'] = $damage;
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
							if (! empty ( $raceResult ) && ! empty ( $expResult )) {
								$role ['health_max'] = $role ['health_max'] + $raceResult ['health_inc'];
								$role ['health'] = $role ['health_max'];
								$parameter = array (
										'level' => $role ['level'],
										'exp' => 0,
										'nextexp' => $expResult ['nextexp'],
										'health_max' => $role ['health_max'],
										'atk' => $role ['atk'] + $raceResult ['atk_inc'],
										'def' => $role ['def'] + $raceResult ['def_inc'],
										'mdef' => $role ['mdef'] + $raceResult ['mdef_inc'],
										'hit' => $role ['hit'] + $raceResult ['hit_inc'],
										'flee' => $role ['flee'] + $raceResult ['flee_inc'] 
								);
							} else {
								$battleResult ['err'] = 2;
							}
						} else {
							$parameter = array (
									'exp' => $role ['exp'] 
							);
						}
					} else {
						$restTime = ceil ( $role ['health_max'] / $recover_health );
					}
					
					$parameter ['health'] = $role ['health'];
					$parameter ['battletime'] = $time;
					if($restTime < $battle_rest_time)
					{
						$restTime = $battle_rest_time;
					}
					$parameter ['next_battletime'] = $time + $restTime;
					
					$battleResult ['timestamp'] = $time;
					$battleResult ['next_battletime'] = $parameter ['next_battletime'];
				}
				$this->load->model ( 'role' );
				$this->role->update ( $this->currentRole ['id'], $parameter );
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
}

?>