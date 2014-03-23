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