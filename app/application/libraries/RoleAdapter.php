<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class RoleAdapter
{
	private $CI;

	public $is_init = false;
	public $role;

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
			if(!empty($role))
			{
				$this->role  = $role[0];

				$this->CI->load->config('const.config');
				$raceConfig = $this->CI->config->item('const_race');
				$jobConfig = $this->CI->config->item('const_job');
				$this->role['race_name'] = $raceConfig['race_' . $this->role['race']];
				$this->role['job_name'] = $jobConfig['job_' . $this->role['job']];
				$this->role['skill'] = empty($this->role['skill']) ? '' : json_decode($this->role['skill'], TRUE);
				$this->role['passive_skill'] = empty($this->role['passive_skill']) ? '' : json_decode($this->role['passive_skill'], TRUE);

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
	}
}