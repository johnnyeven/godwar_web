<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class RoleAdapter
{
	private $CI;

	function __construct()
	{
		$this->CI =& get_instance();
	}

	public function initialization($id)
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
				$role = $role[0];
			}
		}
		
		return false;
	}
}