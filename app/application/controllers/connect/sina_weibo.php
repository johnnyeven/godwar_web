<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
class Sina_weibo extends CI_Controller
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
		$code = $this->input->get_post('code');

		if(!empty($code))
		{
			include_once APPPATH . 'libraries/Weibo.class.php';
			$auth = new SaeTOAuthV2();
			
			$this->load->config('weibo.config');
			$callback_url = $this->config->item('weibo_token_callback');

			$parameter = array(
				'code'			=>	$code,
				'redirect_uri'	=>	$callback_url
			);
			try
			{
				$token = $auth->getAccessToken( 'code', $parameter );

				if(!empty($token))
				{
					$client = new SaeTClientV2( $token['access_token'] );
					$uid = $client->get_uid();
					$uid = $uid['uid'];

					$parameter = array(
						'sina_weibo_id'		=>	$uid,
						'sina_weibo_token'	=>	$token['access_token']
					);
					$this->role->update($this->currentRole->role['id'], $parameter);

					redirect('role/info');
				}
			}
			catch (OAuthException $e)
			{
			}
		}
	}

	public function token_callback()
	{

	}
}