<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );

class Gift extends CI_Controller
{

	public function __construct()
	{
		parent::__construct ();
	}
	
	public function index()
	{
		$result = array(
			array(
				'id'			=>	200001,
				'name'			=>	'重击',
				'comment'		=>	'造成的伤害提升20%',
				'level_limit'	=>	3,
				'skill_limit'	=>	'',
				'is_passive'	=>	0
			),
			array(
				'id'			=>	200002,
				'name'			=>	'躲避',
				'comment'		=>	'防御提升10%，持续三轮',
				'level_limit'	=>	7,
				'skill_limit'	=>	'',
				'is_passive'	=>	0
			)
		);

		echo json_encode($result);
	}
}