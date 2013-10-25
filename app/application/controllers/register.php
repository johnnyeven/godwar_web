<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
class Register extends CI_Controller
{
	private $pageName = 'register';

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->load->view( $this->pageName );
	}

	public function submit()
	{
		$accountName = $this->input->post( 'account_name' );
		$accountPass = $this->input->post( 'account_pass' );
		$accountEmail = $this->input->post( 'account_email' );
		
		if ( !empty( $accountName ) && !empty( $accountPass ) )
		{
			$this->load->helper( 'security' );
			$this->load->model( 'account' );
			
			$time = time();
			$parameter = array (
					'name' => $accountName,
					'pass' => encrypt_pass( $accountPass ),
					'email' => $accountEmail,
					'regtime' => $time,
					'lasttime' => $time 
			);
			$id = $this->account->create( $parameter );
			
			$cookie = array (
					'id' => $id,
					'name' => $accountName 
			);
			$cookieStr = json_encode( $cookie );
			$cookieStr = _authcode( $cookieStr, 'ENCODE' );
			
			$this->load->helper( 'cookie' );
			$cookie = array (
					'name' => 'user',
					'value' => $cookieStr,
					'expire' => $this->config->item( 'cookie_expire' ),
					'domain' => $this->config->item( 'cookie_domain' ),
					'path' => $this->config->item( 'cookie_path' ),
					'prefix' => $this->config->item( 'cookie_prefix' ) 
			);
			$this->input->set_cookie( $cookie );
			
			showMessage( MESSAGE_TYPE_SUCCESS, 'USER_REGISTER_SUCCESS', '', 'index', true, 5 );
		}
		else
		{
			showMessage( MESSAGE_TYPE_ERROR, 'USER_REGISTER_NO_PARAM', '', 'regiser', true, 5 );
		}
	}
}

?>