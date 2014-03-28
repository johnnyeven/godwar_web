<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
class Tencent_weibo extends CI_Controller
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
		$open_id = $this->input->get('openid');
		$open_key = $this->input->get('openkey');

		if(!empty($code))
		{
			include_once APPPATH . 'libraries/Tencent.class.php';
			OAuth::init();
			Tencent::$debug = false;
			
			$this->load->config('tencent_weibo.config');
			$callback_url = $this->config->item('tencent_weibo_auth_callback');

			$access_url = OAuth::getAccessToken( $code, $callback_url );
			$request = Http::request($access_url);
        	parse_str($request, $result);

        	if(!empty($result['access_token']))
        	{
        		$this->currentRole->thirdpart['tencent_weibo_id'] = $open_id;
        		$this->currentRole->thirdpart['tencent_weibo_key'] = $open_key;
        		$this->currentRole->thirdpart['tencent_weibo_token'] = $result['access_token'];
        		$this->currentRole->thirdpart['tencent_weibo_refresh_token'] = $result['refresh_token'];
        		$this->currentRole->thirdpart['tencent_weibo_expire_in'] = $result['expires_in'];
        		$this->currentRole->thirdpart['tencent_weibo_code'] = $code;

				$this->currentRole->save();
				redirect('role/info');
        	}
        	else
        	{
        		showMessage( MESSAGE_TYPE_ERROR, 'AUTH_TENCENT_ERROR_TOKEN_FAIL', '', 'role/info', true, 5 );
        	}
		}
		else
		{
			showMessage( MESSAGE_TYPE_ERROR, 'AUTH_TENCENT_ERROR_AUTH_FAIL', '', 'role/info', true, 5 );
		}
	}

	public function token_callback()
	{

	}
}