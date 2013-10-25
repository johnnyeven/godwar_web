<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
class Login extends CI_Controller
{
	private $pageName = 'login';

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$redirectUrl = $this->input->get( 'redirect' );
		
		$this->load->model( 'utils/check_user', 'check' );
		$user = $this->check->validate( false );
		if ( !empty( $user ) )
		{
			if ( !empty( $redirectUrl ) )
			{
				redirect( urldecode( $redirectUrl ) );
			}
			else
			{
				redirect( 'index' );
			}
		}
		else
		{
			$this->load->view( $this->pageName );
		}
	}

	public function submit()
	{
		$redirectUrl = $this->input->post( 'redirect' );
		$accountName = $this->input->post( 'account_name' );
		$accountPass = $this->input->post( 'account_pass' );
		$cookieRemain = $this->input->post( 'remember' );
		
		if ( !empty( $accountName ) && !empty( $accountPass ) )
		{
			$this->load->model( 'account' );
			$this->load->helper( 'security' );
			
			$parameter = array (
					'name' => $accountName,
					'pass' => encrypt_pass( $accountPass ) 
			);
			$result = $this->account->read( $parameter );
			
			if ( $result === FALSE )
			{
				showMessage( MESSAGE_TYPE_ERROR, 'USER_INVALID', '', 
						'login?redirect=' . $redirectUrl, true, 5 );
			}
			else
			{
				$row = $result[ 0 ];
				$cookie = array (
						'id' => $row->id,
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
				if ( $cookieRemain == '1' )
				{
					$cookie[ 'expire' ] = strval( 
							intval( $this->config->item( 'cookie_expire' ) ) * 30 );
				}
				$this->input->set_cookie( $cookie );
				
				$this->account->update( $row->id, array (
						'lasttime' => time() 
				) );
				
				$redirectUrl = empty( $redirectUrl ) ? 'index' : $redirectUrl;
				showMessage( MESSAGE_TYPE_SUCCESS, 'USER_LOGIN_SUCCESS', '', $redirectUrl, 
						true, 5 );
			}
		}
		else
		{
			showMessage( MESSAGE_TYPE_ERROR, 'USER_LOGIN_ERROR_NO_PARAM', '', 
					'login?redirect=' . $redirectUrl, true, 5 );
		}
	}

	public function out()
	{
		$this->load->helper( 'cookie' );
		
		$cookie = array (
				'name' => 'user',
				'domain' => $this->config->item( 'cookie_domain' ),
				'path' => $this->config->item( 'cookie_path' ),
				'prefix' => $this->config->item( 'cookie_prefix' ) 
		);
		delete_cookie( $cookie );
		showMessage( MESSAGE_TYPE_SUCCESS, 'USER_LOGOUT', '退出成功', 'login', true, 5 );
	}
}

?>