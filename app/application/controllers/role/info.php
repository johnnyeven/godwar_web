<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
class Info extends CI_Controller
{
	private $pageName = 'role/info';
	private $user = null;
	private $currentRole = null;

	public function __construct()
	{
		parent::__construct();
		$this->load->model( 'utils/check_user', 'check' );
		$this->user = $this->check->validate();
		$this->currentRole = $this->check->check_role();
	}

	public function index()
	{
		$this->load->config('weibo.config');
		$this->load->config('tencent_weibo.config');
		$this->load->config('renren.config');
		$weibo_callback_url = $this->config->item('weibo_auth_callback');
		$tencent_callback_url = $this->config->item('tencent_weibo_auth_callback');
		$renren_callback_url = $this->config->item('renren_auth_callback');

		include_once APPPATH . 'libraries/Weibo.class.php';
		$auth = new SaeTOAuthV2();
		$sina_url = $auth->getAuthorizeURL( $weibo_callback_url );

		include_once APPPATH . 'libraries/Tencent.class.php';
		OAuth::init();
		Tencent::$debug = false;
		$tencent_url = OAuth::getAuthorizeURL( $tencent_callback_url );

		include_once APPPATH . 'libraries/rennclient/RennClient.php';
		$rennClient = new RennClient();
		$state = uniqid ( 'renren_', true );
		$renren_url = $rennClient->getAuthorizeURL ( $renren_callback_url, 'code', $state );

		$parameter = array (
			'user'				=>	$this->user,
			'role'				=>	$this->currentRole->role,
			'role_thirdpart'	=>	$this->currentRole->thirdpart,
			'sina_weibo_url'	=>	$sina_url,
			'tencent_weibo_url'	=>	$tencent_url,
			'renren_url'		=>	$renren_url
		);

		$this->load->model( 'utils/render' );
		$this->render->render( $this->pageName, $parameter );
	}
}

?>