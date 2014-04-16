<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

define('MESSAGE_TYPE_SUCCESS', 1);
define('MESSAGE_TYPE_ERROR', 0);

/*
| Error definition
*/
define('ERROR_BATTLE_TIME_NOT_TO', 1);
define('ERROR_ROLE_DEAD', 1042);
define('BATTLE_ERROR_CONFLICT', 11040);

define('GATHER_SUCCESS', 20001);					//采集成功
define('GATHER_NOTHING', 21039);					//没有采到东西
define('GATHER_ERROR_CONFLICT', 21040);				//动作冲突，同一时刻只能选择战斗或者采集
define('GATHER_ERROR_NOT_TIME', 21041);				//时间冷却未到
define('GATHER_ERROR_MAP_NOT_EXIST', 21042);		//地图编号不存在
define('GATHER_ERROR_ITEM_NOT_EXIST', 21045);		//物品编号不存在

define('ITEM_SELL_SUCCESS', 1);						//物品出售成功
define('ITEM_SELL_ERROR_LOCKED', 1043);				//物品被锁定
define('ITEM_SELL_ERROR_NOT_ENOUGH', 1044);			//指定数量超过当前持有量
define('ITEM_SELL_ERROR_NOT_EXIST', 1045);			//指定物品不存在
define('ITEM_SELL_ERROR_NO_PARAM', 1046);			//参数不正确

define('ITEM_LOCK_SUCCESS', 1);						//成功
define('ITEM_LOCK_ERROR_NOT_EXIST', 1045);			//指定物品不存在
define('ITEM_LOCK_ERROR_NO_PARAM', 1046);			//参数不正确

define('ITEM_UNLOCK_SUCCESS', 1);					//成功
define('ITEM_UNLOCK_ERROR_NOT_EXIST', 1045);		//指定物品不存在
define('ITEM_UNLOCK_ERROR_NO_PARAM', 1046);			//参数不正确

define('ALCHEMY_LEARN_SUCCESS', 1);					//学习配方成功
define('ALCHEMY_LEARN_ERROR_LOCKED', 1043);			//物品被锁定
define('ALCHEMY_LEARN_ERROR_NOT_ENOUGH', 1044);		//指定数量超过当前持有量
define('ALCHEMY_LEARN_ERROR_NOT_EXIST', 1045);		//指定物品不存在
define('ALCHEMY_LEARN_ERROR_NO_PARAM', 1046);		//参数不正确
define('ALCHEMY_LEARN_ERROR_EXIST', 1047);			//指定配方已经学习

define('ALCHEMY_INFO_SUCCESS', 1);					//显示配方成功
define('ALCHEMY_INFO_ERROR_NO_PARAM', 1046);		//参数不正确
define('ALCHEMY_INFO_ERROR_NOT_EXIST', 1047);		//指定配方不存在

define('ALCHEMY_BUILD_SUCCESS', 1);					//显示配方成功
define('ALCHEMY_BUILD_ERROR_NO_PARAM', 1046);		//参数不正确
define('ALCHEMY_BUILD_ERROR_NOT_EXIST', 1047);		//指定配方不存在
define('ALCHEMY_BUILD_ERROR_NOT_ENOUGH', 1044);		//指定配方材料不足
define('ALCHEMY_BUILD_ERROR_MAX_QUEUE', 1048);		//队列已满

define('ALCHEMY_RECEIVE_SUCCESS', 1);				//显示配方成功
define('ALCHEMY_RECEIVE_ERROR_NO_PARAM', 1046);		//参数不正确
define('ALCHEMY_RECEIVE_ERROR_NOT_EXIST', 1047);	//指定配方不存在

define('ITEM_USE_SUCCESS', 1);						//使用道具成功
define('ITEM_USE_ERROR_LOCKED', 1043);				//道具被锁定
define('ITEM_USE_ERROR_NOT_ENOUGH', 1044);			//道具不足
define('ITEM_USE_ERROR_NO_PARAM', 1046);			//参数不正确
define('ITEM_USE_ERROR_NOT_EXIST', 1047);			//道具不存在
define('ITEM_USE_ERROR_TYPE_ERROR', 1049);			//道具类型错误

/* End of file constants.php */
/* Location: ./application/config/constants.php */