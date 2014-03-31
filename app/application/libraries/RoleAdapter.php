<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class RoleAdapter
{
	private $CI;

	public $is_init = false;
	public $role;
	public $thirdpart;

	function __construct($id)
	{
		if(!empty($id))
		{
			$this->CI =& get_instance();
			$this->initialization($id);
		}
		else
		{
			throw new Exception("RoleAdapter需要角色ID初始化");
		}
	}

	private function initialization($id)
	{
		if(!empty($id))
		{
			$this->CI->load->model('role');
			$parameter = array(
				'id'	=>	$id
			);
			$role = $this->CI->role->read($parameter);

			$this->CI->load->model('mthirdpart');
			$parameter = array(
				'role_id'	=>	$id
			);
			$thirdpart = $this->CI->mthirdpart->read($parameter);
			if(!empty($role) && !empty($thirdpart))
			{
				$this->role  = $role[0];
				$this->thirdpart  = $thirdpart[0];

				$this->CI->load->config('const.config');
				$raceConfig = $this->CI->config->item('const_race');
				$jobConfig = $this->CI->config->item('const_job');
				$this->role['race_name'] = $raceConfig['race_' . $this->role['race']];
				$this->role['job_name'] = $jobConfig['job_' . $this->role['job']];
				$this->role['skill'] = empty($this->role['skill']) ? array() : json_decode($this->role['skill'], TRUE);
				$this->role['passive_skill'] = empty($this->role['passive_skill']) ? array() : json_decode($this->role['passive_skill'], TRUE);
				$this->role['gift'] = empty($this->role['gift']) ? array() : json_decode($this->role['gift'], TRUE);

				$this->is_init = true;
			}
			else
			{
				throw new Exception("RoleAdapter角色ID不存在");
			}
		}
		
		return false;
	}

	public function role_level_up()
	{
		if(!isset($this->CI->mongo_db))
		{
			$this->CI->load->library('Mongo_db');
		}

		++ $this->role ['level'];
		$param = array (
				'id' => $this->role ['race'] 
		);
		$raceResult = $this->CI->mongo_db->where ( $param )->get ( 'race' );
		$raceResult = $raceResult [0];
		
		$param = array (
				'level' => intval ( $this->role ['level'] ) 
		);
		$expResult = $this->CI->mongo_db->where ( $param )->get ( 'exp' );
		$expResult = $expResult [0];

		$param = array(
				'id'	=>	intval ( $this->role ['job'] )
		);
		$jobResult = $this->CI->mongo_db->where ( $param )->get ( 'job' );
		$jobResult = $jobResult [0];

		if(!empty($raceResult) && !empty($expResult))
		{
			$this->calculate_property($raceResult, $jobResult);

			$this->role['exp'] = 0;
			$this->role['nextexp'] = $expResult ['nextexp'];
		}
		else
		{
			return false;
		}
	}

	public function calculate_property($raceResult, $jobResult)
	{
		$tmp_base = $this->role ['health_base'] + $this->role['level'] * $raceResult['health_inc'];
		$this->role ['health_max'] = $tmp_base; //种族加成
		$this->role ['health_max'] += ($jobResult['health_add'] + $tmp_base * $jobResult['health_inc']); //职业加成
		$this->role ['health'] = $this->role ['health_max'];

		$tmp_base = $this->role ['atk_base'] + $this->role['level'] * $raceResult['atk_inc'];
		$this->role ['atk'] = $tmp_base; // 种族加成
		$this->role ['atk'] += ($jobResult['atk_add'] + $tmp_base * $jobResult['atk_inc']); // 职业加成

		$tmp_base = $this->role ['def_base'] + $this->role['level'] * $raceResult['def_inc'];
		$this->role ['def'] = $tmp_base; // 种族加成
		$this->role ['def'] += ($jobResult['def_add'] + $tmp_base * $jobResult['def_inc']); // 职业加成

		$tmp_base = $this->role ['mdef_base'] + $this->role['level'] * $raceResult['mdef_inc'];
		$this->role ['mdef'] = $tmp_base; // 种族加成
		$this->role ['mdef'] += ($jobResult['mdef_add'] + $tmp_base * $jobResult['mdef_inc']); // 职业加成

		$tmp_base = $this->role ['hit_base'] + $this->role['level'] * $raceResult['hit_inc'];
		$this->role ['hit'] = $tmp_base; // 种族加成
		$this->role ['hit'] += ($jobResult['hit_add'] + $tmp_base * $jobResult['hit_inc']); // 职业加成

		$tmp_base = $this->role ['flee_base'] + $this->role['level'] * $raceResult['flee_inc'];
		$this->role ['flee'] = $tmp_base; // 种族加成
		$this->role ['flee'] += ($jobResult['flee_add'] + $tmp_base * $jobResult['flee_inc']); // 职业加成

		//技能加成
		if(!empty($this->role ['passive_skill']))
		{
			$passive_skill = json_decode($this->role ['passive_skill']);
			foreach($passive_skill as $passive)
			{
				$skillId = 'skill_' . $passive;
				$this->load->model ( "skills/{$skillId}" );
				$damage = $this->$skillId->execute ( $this->role, null );
			}
		}

		if(!isset($this->CI->mequipment))
		{
			$this->CI->load->model('mequipment');
		}

		$parameter = array(
			'role_id'		=>	$this->role['id'],
			'is_equipped'	=>	1,
			'is_destroyed'	=>	0
		);
		$result = $this->CI->mequipment->read($parameter);

		foreach($result as $equipment)
		{
			$this->role ['atk'] += ($equipment['atk_base'] + $equipment['atk_inc']);
			$this->role ['def'] += ($equipment['def_base'] + $equipment['def_inc']);
			$this->role ['mdef'] += ($equipment['mdef_base'] + $equipment['mdef_inc']);
			$this->role ['health_max'] += ($equipment['health_max_base'] + $equipment['health_max_inc']);
			$this->role ['health'] += ($equipment['health_max_base'] + $equipment['health_max_inc']);
			$this->role ['hit'] += ($equipment['hit_base'] + $equipment['hit_inc']);
			$this->role ['flee'] += ($equipment['flee_base'] + $equipment['flee_inc']);
		}
	}

	public function save()
	{
		$this->CI->load->model('role');
		$parameter = array(
			'level'					=>	$this->role['level'],
			'gold'					=>	$this->role['gold'],
			'exp'					=>	$this->role['exp'],
			'nextexp'				=>	$this->role['nextexp'],
			'job'					=>	$this->role['job'],
			'health_base'			=>	$this->role['health_base'],
			'health_max'			=>	$this->role['health_max'],
			'health'				=>	$this->role['health'],
			'atk_base'				=>	$this->role['atk_base'],
			'atk'					=>	$this->role['atk'],
			'def_base'				=>	$this->role['def_base'],
			'def'					=>	$this->role['def'],
			'mdef_base'				=>	$this->role['mdef_base'],
			'mdef'					=>	$this->role['mdef'],
			'hit_base'				=>	$this->role['hit_base'],
			'hit'					=>	$this->role['hit'],
			'crit_base'				=>	$this->role['crit_base'],
			'crit'					=>	$this->role['crit'],
			'flee_base'				=>	$this->role['flee_base'],
			'flee'					=>	$this->role['flee'],
			'skill_trigger_base'	=>	$this->role['skill_trigger_base'],
			'skill_trigger'			=>	$this->role['skill_trigger'],
			'skill'					=>	json_encode($this->role['skill']),
			'main_skill'			=>	$this->role['main_skill'],
			'passive_skill'			=>	json_encode($this->role['passive_skill']),
			'gift'					=>	json_encode($this->role['gift']),
			'map_id'				=>	$this->role['map_id'],
			'battletime'			=>	$this->role['battletime'],
			'next_battletime'		=>	$this->role['next_battletime']
		);
		$this->CI->role->update($this->role['id'], $parameter);

		$this->CI->load->model('mthirdpart');
		$parameter = array(
			'sina_weibo_id'					=>	$this->thirdpart['sina_weibo_id'],
			'sina_weibo_token'				=>	$this->thirdpart['sina_weibo_token'],
			'sina_weibo_nickname'			=>	$this->thirdpart['sina_weibo_nickname'],
			'tencent_weibo_nickname'		=>	$this->thirdpart['tencent_weibo_nickname'],
			'tencent_weibo_code'			=>	$this->thirdpart['tencent_weibo_code'],
			'tencent_weibo_expire_in'		=>	$this->thirdpart['tencent_weibo_expire_in'],
			'tencent_weibo_refresh_token'	=>	$this->thirdpart['tencent_weibo_refresh_token'],
			'tencent_weibo_token'			=>	$this->thirdpart['tencent_weibo_token'],
			'tencent_weibo_key'				=>	$this->thirdpart['tencent_weibo_key'],
			'tencent_weibo_id'				=>	$this->thirdpart['tencent_weibo_id']
		);
		$this->CI->mthirdpart->update($this->role['id'], $parameter);
	}
}