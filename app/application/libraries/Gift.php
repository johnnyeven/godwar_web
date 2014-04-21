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
	//	'type'			=>	'gift', //gift, item, status
	// 	'gift_id'		=>	'1700001'
	// );
	public function hook($param)
	{
		if(is_array($param) && isset($param['gift_id']))
		{
			if($param['type'] == 'gift')
			{
				$filepath = APPPATH . 'models/gifts/gift_';
				$class = 'Gift_';
			}
			elseif($param['type'] == 'status')
			{
				$filepath = APPPATH . 'models/skills/status_';
				$class = 'Status_';
			}
			$class .= $param['gift_id'];
			$filepath .= $param['gift_id'] . '.php';

			if ( ! file_exists($filepath))
			{
				return FALSE;
			}

			if ( ! class_exists($class))
			{
				require($filepath);
			}

			if(!isset($this->hooks[$param['action']]))
			{
				$this->hooks[$param['action']] = array();
			}

			array_push($this->hooks[$param['action']], $param);
		}
		return false;
	}

	public function call_hook($action, & $parameter)
	{
		if(empty($action))
		{
			return;
		}
		if(isset($this->hooks[$action]))
		{
			foreach($this->hooks[$action] as $hook)
			{
				// echo "hook_name: {$action}\n";
				// echo "gift_id: {$hook['gift_id']}\n";
				// var_dump($parameter);
				$this->_run_hook($hook, $parameter);
				// var_dump($parameter);
			}
		}
	}

	private function _run_hook($hook, & $parameter)
	{
		if($this->in_progress)
		{
			return false;
		}

		if(is_array($hook))
		{
			if (isset($hook['gift_id']))
			{
				if($hook['type'] == 'gift')
				{
					$filepath = APPPATH . 'models/gifts/gift_';
					$class = 'Gift_';
				}
				elseif($hook['type'] == 'status')
				{
					$filepath = APPPATH . 'models/skills/status_';
					$class = 'Status_';
				}
				$class .= $hook['gift_id'];
				$filepath .= $hook['gift_id'] . '.php';

				if ( ! file_exists($filepath))
				{
					return FALSE;
				}

				$this->in_progress = true;

				if ( ! class_exists($class))
				{
					require($filepath);
				}

				$GIFT = new $class();
				$GIFT->execute($parameter);
				$this->in_progress = false;
				return true;
			}
		}

		return false;
	}
}