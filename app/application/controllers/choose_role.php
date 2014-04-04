<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
class Choose_role extends CI_Controller
{
	private $pageName = 'choose_role';
	private $user = null;

	public function __construct()
	{
		parent::__construct();
		$this->load->model( 'utils/check_user', 'check' );
		$this->user = $this->check->validate();
	}

	public function index()
	{
		$this->load->model( 'role' );
		
		$parameter = array (
				'account_id' => $this->user['id'] 
		);
		$role = $this->role->read( $parameter );
		if ( empty( $role ) )
		{
			redirect( 'create_role' );
			return;
		}
		if ( count( $role ) == 1 )
		{
			$this->load->helper( 'cookie' );
			$this->load->helper( 'security' );
			$role = $role[ 0 ];
			$cookie = array (
					'id' => $role['id'],
					'name' => $role['role_name'] 
			);
			$cookieStr = json_encode( $cookie );
			$cookieStr = _authcode( $cookieStr, 'ENCODE' );
			
			$this->load->helper( 'cookie' );
			$cookie = array (
					'name' => 'role',
					'value' => $cookieStr,
					'expire' => 0,
					'domain' => $this->config->item( 'cookie_domain' ),
					'path' => $this->config->item( 'cookie_path' ),
					'prefix' => $this->config->item( 'cookie_prefix' ),
					'expire' => $this->config->item( 'cookie_expire' )
			);
			$this->input->set_cookie( $cookie );
			
			redirect( 'role/info' );
		}
		else
		{
			$parameter = array(
					'roles'		=>	$role
			);
			$this->load->view( $this->pageName, $parameter );
		}
	}

	public function submit()
	{
		$this->load->model( 'role' );
		
		$roleId = $this->input->post( 'id' );
		$parameter = array (
				'id' => $roleId 
		);
		$role = $this->role->read( $parameter );
		if ( !empty( $role ) )
		{
			$role = $role[ 0 ];
			if ( $role['account_id'] == $this->user['id'] )
			{
				$this->load->helper( 'cookie' );
				$this->load->helper( 'security' );
				$cookie = array (
						'id' => $role['id'],
						'name' => $role['role_name'] 
				);
				$cookieStr = json_encode( $cookie );
				$cookieStr = _authcode( $cookieStr, 'ENCODE' );
				
				$this->load->helper( 'cookie' );
				$cookie = array (
						'name' => 'role',
						'value' => $cookieStr,
						'expire' => 0,
						'domain' => $this->config->item( 'cookie_domain' ),
						'path' => $this->config->item( 'cookie_path' ),
						'prefix' => $this->config->item( 'cookie_prefix' ) 
				);
				$this->input->set_cookie( $cookie );
				
				redirect( 'role/info' );
			}
			else
			{
				showMessage( MESSAGE_TYPE_ERROR, 'ROLE_NOT_MATCH', '', 'choose_role', true, 5 );
			}
		}
		else
		{
			showMessage( MESSAGE_TYPE_ERROR, 'ROLE_NOT_EXIST', '', 'choose_role', true, 5 );
		}
	}
}

?>