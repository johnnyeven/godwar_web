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
		// $this->role ['passive_skill'];
		$this->role ['health_base'] += $raceResult ['health_inc'];
		$this->role ['health_max'] = $this->role ['health_base']; //种族加成
		$this->role ['health_max'] += ($jobResult['health_add'] + $this->role ['health_base'] * $jobResult['health_inc']); //职业加成
		$this->role ['health'] = $this->role ['health_max'];

		$this->role ['atk_base'] += $raceResult ['atk_inc'];
		$this->role ['atk'] = $this->role ['atk_base']; // 种族加成
		$this->role ['atk'] += ($jobResult['atk_add'] + $this->role ['atk_base'] * $jobResult['atk_inc']); // 职业加成

		$this->role ['def_base'] += $raceResult ['def_inc'];
		$this->role ['def'] = $this->role ['def_base']; // 种族加成
		$this->role ['def'] += ($jobResult['def_add'] + $this->role ['def_base'] * $jobResult['def_inc']); // 职业加成

		$this->role ['mdef_base'] += $raceResult ['mdef_inc'];
		$this->role ['mdef'] = $this->role ['mdef_base']; // 种族加成
		$this->role ['mdef'] += ($jobResult['mdef_add'] + $this->role ['mdef_base'] * $jobResult['mdef_inc']); // 职业加成

		$this->role ['hit_base'] += $raceResult ['hit_inc'];
		$this->role ['hit'] = $this->role ['hit_base']; // 种族加成
		$this->role ['hit'] += ($jobResult['hit_add'] + $this->role ['hit_base'] * $jobResult['hit_inc']); // 职业加成

		$this->role ['flee_base'] += $raceResult ['flee_inc'];
		$this->role ['flee'] = $this->role ['flee_base']; // 种族加成
		$this->role ['flee'] += ($jobResult['flee_add'] + $this->role ['flee_base'] * $jobResult['flee_inc']); // 职业加成
	}
}