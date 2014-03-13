<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gift
{
	private $hooks = array();
	private $in_progress = FALSE;

	function __construct()
	{

	}

	// $param = array(
	// 	'action'		=>	'pre_battle',
	// 	'gift_id'		=>	'1700001',
	// 	'parameter'		=>	& $attacker
	// );
	public function hook($param)
	{
		if(is_array($param) && isset($param['gift_id']))
		{
			$filepath = APPPATH . 'models/gifts/gift_' . $param['gift_id'] . '.php';

			if ( ! file_exists($filepath))
			{
				return FALSE;
			}

			$class = 'Gift_' . $param['gift_id'];
			if ( ! class_exists($class))
			{
				require($filepath);
			}

			$GIFT = new $class();
			if($GIFT->can_hooked($param['action']))
			{
				if(!isset($this->hooks[$param['action']]))
				{
					$this->hooks[$param['action']] = array();
				}

				$param['gift'] = $GIFT;
				array_push($this->hooks[$param['action']], $param);

				return true;
			}
		}
		return false;
	}

	public function call_hook($action)
	{
		if(empty($action))
		{
			return;
		}

		if(isset($this->hooks[$action]))
		{
			foreach($this->hooks[$action] as $hook)
			{
				$this->_run_hook($hook);
			}
		}
	}

	private function _run_hook($hook)
	{
		if($this->in_progress)
		{
			return false;
		}

		if(is_array($hook))
		{
			if (isset($hook['gift']))
			{
				$hook['gift']->execute($hook['parameter']);
			}
			else if (isset($hook['gift_id']))
			{
				$filepath = APPPATH . 'models/gifts/gift_' . $hook['gift_id'] . '.php';

				if ( ! file_exists($filepath))
				{
					return FALSE;
				}

				$this->in_progress = true;

				$class = 'Gift_' . $hook['gift_id'];
				if ( ! class_exists($class))
				{
					require($filepath);
				}

				$GIFT = new $class();
				$GIFT->execute($hook['parameter']);
			}
		}
	}
}