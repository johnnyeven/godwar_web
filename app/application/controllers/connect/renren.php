<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
class Renren extends CI_Controller
{
	private $user = null;
	private $currentRole = null;

	public function __construct()
	{
		parent::__construct();
		$this->load->model( 'utils/check_user', 'check' );
		$this->user = $this->check->validate();
		$this->currentRole = $this->check->check_role();
	}

	public function auth_callback()
	{
		$code = $this->input->get('code');

		if(!empty($code))
		{
			include_once APPPATH . 'libraries/rennclient/RennClient.php';
			$rennClient = new RennClient();
			
			$this->load->config('renren.config');
			$callback_url = $this->config->item('renren_auth_callback');

			$parameter = array(
				'code'			=>	$code,
				'redirect_uri'	=>	$callback_url
			);

			try
			{
				$token = $rennClient->getTokenFromTokenEndpoint ( 'code', $parameter );
			}
			catch(RennException $e)
			{
				return false;
			}

        	if(!empty($token))
        	{
        		// 获得用户接口
				$user_service = $rennClient->getUserService ();
				// 获得当前登录用户
				$user = $user_service->getUserLogin ();
				
        		$this->currentRole->thirdpart['renren_id'] = strval($user['id']);
        		$this->currentRole->thirdpart['renren_name'] = $user['name'];

				$this->currentRole->save();
				redirect('role/info');
        	}
        	else
        	{
        		showMessage( MESSAGE_TYPE_ERROR, 'AUTH_RENREN_ERROR_TOKEN_FAIL', '', 'role/info', true, 5 );
        	}
		}
		else
		{
			showMessage( MESSAGE_TYPE_ERROR, 'AUTH_RENREN_ERROR_AUTH_FAIL', '', 'role/info', true, 5 );
		}
	}

	public function token_callback()
	{

	}
}