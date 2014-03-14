<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//天赋hook点位置
$config['gifts_hook_action_name'] = array(
	'110001'	=>	array( 'after_billing_buy', 'after_billing_sell' ),
	'110002'	=>	array( 'before_level_up' ),
	'120001'	=>	array( 'after_level_up' ),
	'120002'	=>	array( 'before_settle_battle' ),
	'130001'	=>	array( 'before_settle_battle' ),
	'130002'	=>	array( 'before_settle_battle' ),
	'140001'	=>	array( 'after_level_up' ),
	'140002'	=>	array( 'before_make_equipment', 'before_upgrade_equipment', 'before_enchant_equipment' ),
	'150001'	=>	array( 'after_level_up' ),
	'150002'	=>	array( 'before_alchemy' ),
	'160001'	=>	array( 'after_level_up' ),
	'160002'	=>	array( 'after_level_up' )
);