<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class RoleAdapter
{
	private $CI;

	public $is_init = false;
	public $id;
	public $account_id;
	public $name;
	public $level;
	public $gold;
	public $exp;
	public $nextexp;
	public $race;
	public $race_name;
	public $job;
	public $job_name;
	public $health_base;
	public $health_max;
	public $health;
	public $atk_base;
	public $atk;
	public $def_base;
	public $def;
	public $mdef_base;
	public $mdef;
	public $hit_base;
	public $hit;
	public $crit_base;
	public $crit;
	public $flee_base;
	public $flee;
	public $gift;
	public $skill_trigger_base;
	public $skill_trigger;
	public $skill;
	public $main_skill;
	public $createtime;
	public $lasttime;
	public $map_id;
	public $battletime;
	public $next_battletime;

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
				$role  = $role[0];
				var_dump($role);
				exit();

				$this->id = intval($role['id']);
				$this->account_id = intval($role['account_id']);
				$this->name = $role['name'];
				$this->level = intval($role['level']);
				$this->gold = intval($role['gold']);
				$this->exp = intval($role['exp']);
				$this->nextexp = intval($role['nextexp']);
				$this->race = $role['race'];
				$this->job = $role['job'];
				$this->health_base = intval($role['health_base']);
				$this->health_max = intval($role['health_max']);
				$this->health = intval($role['health']);
				$this->atk_base = intval($role['atk_base']);
				$this->atk = intval($role['atk']);
				$this->def_base = intval($role['def_base']);
				$this->def = intval($role['def']);
				$this->mdef_base = intval($role['mdef_base']);
				$this->mdef = intval($role['mdef']);
				$this->hit_base = intval($role['hit_base']);
				$this->hit = intval($role['hit']);
				$this->crit_base = intval($role['crit_base']);
				$this->crit = intval($role['crit']);
				$this->flee_base = intval($role['flee_base']);
				$this->flee = intval($role['flee']);
				$this->gift = $role['gift'];
				$this->skill_trigger_base = floatval($role['skill_trigger_base']);
				$this->skill_trigger = floatval($role['skill_trigger']);
				$this->skill = $role['skill'];
				$this->main_skill = $role['main_skill'];
				$this->createtime = $role['createtime'];
				$this->lasttime = $role['lasttime'];
				$this->map_id = intval($role['map_id']);
				$this->battletime = intval($role['battletime']);
				$this->next_battletime = intval($role['next_battletime']);

				$this->CI->load->config('const.config');
				$raceConfig = $this->CI->config->item('const_race');
				$jobConfig = $this->CI->config->item('const_job');
				$this->race_name = $raceConfig['race_' . $this->race];
				$this->job_name = $jobConfig['job_' . $this->job];

				$this->is_init = true;
			}
			else
			{
				throw new Exception("RoleAdapter角色ID不存在");
			}
		}
		
		return false;
	}
}