var ERROR_BATTLE_TIME_NOT_TO = 1;
var ERROR_ROLE_DEAD = 1042;
var BATTLE_ERROR_CONFLICT = 11040;

var GATHER_SUCCESS = 20001;						//采集成功
var GATHER_NOTHING = 21039;						//没有采到东西
var GATHER_ERROR_CONFLICT = 21040;				//动作冲突，同一时刻只能选择战斗或者采集
var GATHER_ERROR_NOT_TIME = 21041;				//时间冷却未到
var GATHER_ERROR_MAP_NOT_EXIST = 21042;			//地图编号不存在
var GATHER_ERROR_ITEM_NOT_EXIST = 21045;		//物品编号不存在

var EQUIPMENT_MARKET_SELL_SUCCESS = 30001;		//寄售成功
var EQUIPMENT_MARKET_ERROR_NOT_ENOUGH = 31035;	//物品数量不足
var EQUIPMENT_MARKET_ERROR_EQUIPPED = 31038;	//物品已装备
var EQUIPMENT_MARKET_ERROR_LOCKED = 31043;		//物品已锁定
var EQUIPMENT_MARKET_ERROR_NOT_EXIST = 31045;	//物品不存在
var EQUIPMENT_MARKET_ERROR_NO_PARAM = 31046;	//参数不正确

var MARKET_CANCEL_SUCCESS = 40001;				//取消订单成功
var MARKET_BUY_SUCCESS = 40002;					//购买订单成功
var MARKET_ERROR_SELF_ORDER = 41037;			//不能购买自己的订单
var MARKET_ERROR_NOT_ENOUGH_GOLD = 41036;		//金币不足
var MARKET_ERROR_NOT_EXIST = 41045;				//订单不存在
var MARKET_ERROR_NO_PARAM = 41046;				//参数不正确

var REBIRTH_SUCCESS = 50001;					//重生
var REBIRTH_ERROR_NOT_DEAD = 51009;				//角色没有死亡

var ITEM_SELL_SUCCESS = 1;						//物品出售成功
var ITEM_SELL_ERROR_LOCKED = 1043;				//物品被锁定
var ITEM_SELL_ERROR_NOT_ENOUGH = 1044;			//指定数量超过当前持有量
var ITEM_SELL_ERROR_NOT_EXIST = 1045;			//指定物品不存在
var ITEM_SELL_ERROR_NO_PARAM = 1046;			//参数不正确

var ITEM_LOCK_SUCCESS = 1;						//成功
var ITEM_LOCK_ERROR_NOT_EXIST = 1045;			//指定物品不存在
var ITEM_LOCK_ERROR_NO_PARAM = 1046;			//参数不正确

var ITEM_UNLOCK_SUCCESS = 1;					//成功
var ITEM_UNLOCK_ERROR_NOT_EXIST = 1045;			//指定物品不存在
var ITEM_UNLOCK_ERROR_NO_PARAM = 1046;			//参数不正确

var ALCHEMY_LEARN_SUCCESS = 1;					//学习配方成功
var ALCHEMY_LEARN_ERROR_LOCKED = 1043;			//物品被锁定
var ALCHEMY_LEARN_ERROR_NOT_ENOUGH = 1044;		//指定数量超过当前持有量
var ALCHEMY_LEARN_ERROR_NOT_EXIST = 1045;		//指定物品不存在
var ALCHEMY_LEARN_ERROR_NO_PARAM = 1046;		//参数不正确
var ALCHEMY_LEARN_ERROR_EXIST = 1047;			//指定配方已经学习

var ALCHEMY_INFO_SUCCESS = 1;					//显示配方成功
var ALCHEMY_INFO_ERROR_NO_PARAM = 1046;			//参数不正确
var ALCHEMY_INFO_ERROR_NOT_EXIST = 1047;		//指定配方不存在

var ALCHEMY_BUILD_SUCCESS = 1;					//显示配方成功
var ALCHEMY_BUILD_ERROR_NO_PARAM = 1046;		//参数不正确
var ALCHEMY_BUILD_ERROR_NOT_EXIST = 1047;		//指定配方不存在
var ALCHEMY_BUILD_ERROR_NOT_ENOUGH = 1044;		//指定配方材料不足
var ALCHEMY_BUILD_ERROR_MAX_QUEUE = 1048;		//队列已满

var ALCHEMY_RECEIVE_SUCCESS = 1;				//显示配方成功
var ALCHEMY_RECEIVE_ERROR_NO_PARAM = 1046;		//参数不正确
var ALCHEMY_RECEIVE_ERROR_NOT_EXIST = 1047;		//指定配方不存在

var ITEM_USE_SUCCESS = 1;						//使用道具成功
var ITEM_USE_ERROR_LOCKED = 1043;				//道具被锁定
var ITEM_USE_ERROR_NOT_ENOUGH = 1044;			//道具不足
var ITEM_USE_ERROR_NO_PARAM = 1046;				//参数不正确
var ITEM_USE_ERROR_NOT_EXIST = 1047;			//道具不存在
var ITEM_USE_ERROR_TYPE_ERROR = 1049;			//道具类型错误