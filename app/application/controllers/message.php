<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
class Message extends CI_Controller
{
	/**
	 *
	 *
	 * 消息显示
	 *
	 * 提供消息显示的逻辑层
	 *
	 * @author johnnyEven
	 * @version Pulse message.php - 1.0.1.20130131 10:49
	 */
	private $rootPath;
	private $pageName = 'message';

	public function __construct()
	{
		parent::__construct();
		$this->rootPath = $this->config->item( 'root_path' );
	}

	public function index()
	{
		$type = $this->input->get_post( 'type', TRUE );
		$info = $this->input->get_post( 'info', TRUE );
		$message = $this->input->get_post( 'message' );
		$redirect = $this->input->get_post( 'redirect', TRUE );
		$autoRedirect = $this->input->get_post( 'auto_redirect', TRUE );
		$autoDelay = $this->input->get_post( 'auto_delay', TRUE );
		
		$redirect = empty( $redirect ) ? 'javascript: history.back(-1);' : $redirect;
		
		if ( $autoRedirect == '1' )
		{
			$metaData = "<meta http-equiv=\"refresh\" content=\"{$autoDelay}; url=$redirect\" />";
			$returnContent = "系统将在{$autoDelay}秒内自动跳转，或者您也可以点<a href=\"$redirect\">这里</a>\n";
		}
		else
		{
			$metaData = '';
			$returnContent = "<a href=\"$redirect\">点击这里返回</a>\n";
		}
		
		$this->load->helper( 'language' );
		
		$parameter = array (
				'meta_data' => $metaData,
				'return_content' => $returnContent,
				'type' => $type,
				'info' => $info,
				'message' => $message,
				'redirect' => $redirect,
				'autoRedirect' => $autoRedirect,
				'autoDelay' => $autoDelay 
		);
		
		$this->load->view( $this->pageName, $parameter );
	}
}
?>