<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
class Check_user extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function validate( $redirect = true )
	{
		$this->load->model( 'account' );
		
		$this->load->helper( 'security' );
		$this->load->helper( 'cookie' );
		
		$redirectUrl = 'login?redirect=' . urlencode( $this->input->server( 'REQUEST_URI' ) );
		$cookieName = $this->config->item( 'cookie_prefix' ) . 'user';
		if ( !$this->input->cookie( $cookieName, TRUE ) )
		{
			if ( $redirect )
			{
				showMessage( MESSAGE_TYPE_ERROR, 'USER_CHECK_EXPIRED', '', $redirectUrl, true, 5 );
			}
		}
		else
		{
			$cookie = $this->input->cookie( $cookieName, TRUE );
			$cookie = _authcode( $cookie );
			$json = json_decode( $cookie );
			$id = $json->id;
			$parameter = array (
					'id' => $id 
			);
			$result = $this->account->read( $parameter );
			if ( $result != FALSE )
			{
				
				return $result[ 0 ];
			}
			else
			{
				
				$this->resetCookie();
				if ( $redirect )
				{
					showMessage( MESSAGE_TYPE_ERROR, 'USER_CHECK_INVALID', '', $redirectUrl, true, 
							5 );
				}
			}
		}
	}
	
	public function check_role( $redirect = true )
	{
		$this->load->model('role');
		
		$this->load->helper( 'security' );
		$this->load->helper( 'cookie' );
		
		$redirectUrl = 'login';
		$cookieName = $this->config->item( 'cookie_prefix' ) . 'role';

		if ( !$this->input->cookie( $cookieName, TRUE ) )
		{
			if ( $redirect )
			{
				showMessage( MESSAGE_TYPE_ERROR, 'ROLE_CHECK_EXPIRED', '', $redirectUrl, true, 5 );
			}
		}
		else
		{
			$cookie = $this->input->cookie( $cookieName, TRUE );
			$cookie = _authcode( $cookie );
			$json = json_decode( $cookie );
			$id = $json->id;
			
			include_once APPPATH . 'libraries/RoleAdapter.php';
			$role_adapter = new RoleAdapter($id);
			var_dump($role_adapter);
			exit();
			if($role_adapter->is_init)
			{
				return $role_adapter;
			}
			else
			{
		
				$this->resetRoleCookie();
				if ( $redirect )
				{
					showMessage( MESSAGE_TYPE_ERROR, 'ROLE_CHECK_INVALID', '', $redirectUrl, true,
					5 );
				}
			}
		}
	}

	private function resetCookie()
	{
		$this->load->helper( 'cookie' );
		$cookie = array (
				'name' => 'user',
				'domain' => $this->config->item( 'cookie_domain' ),
				'path' => $this->config->item( 'cookie_path' ),
				'prefix' => $this->config->item( 'cookie_prefix' ) 
		);
		delete_cookie( $cookie );
	}

	private function resetRoleCookie()
	{
		$this->load->helper( 'cookie' );
		$cookie = array (
				'name' => 'role',
				'domain' => $this->config->item( 'cookie_domain' ),
				'path' => $this->config->item( 'cookie_path' ),
				'prefix' => $this->config->item( 'cookie_prefix' ) 
		);
		delete_cookie( $cookie );
	}
}
?>