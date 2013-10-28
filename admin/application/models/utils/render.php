<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Render extends CI_Model
{
	/**
	 * 
	 * 渲染器
	 * 
	 * 提供页面最终渲染的拼接操作
	 * 
	 * @author Johnny EVEN
	 * @version Pulse utils/render.php - 1.0.1.20130123 17:32
	 */
	public function __construct()
	{
		parent::__construct();
	}
	
	public function render($pageName = null, $data = null)
	{
		$header = $this->load->view('std_header', $data, true);
		$top = $this->load->view('std_top', $data, true);
		$content = $this->load->view("{$pageName}_view", $data, true);
		$footer = $this->load->view('std_footer', $data, true);
		
		$value = array(
			'std_header'	=>	$header,
			'std_top'		=>	$top,
			'content'		=>	$content,
			'std_footer'	=>	$footer
		);
		$this->load->view('std_frame', $value);
	}
	
	public function renderUserCenter($pageName = null, $data = null)
	{
		$header = $this->load->view('user/std_header', $data, true);
		$top = $this->load->view('user/std_top', $data, true);
		$left = $this->load->view('user/std_left', $data, true);
		$content = $this->load->view("user/{$pageName}_view", $data, true);
		$footer = $this->load->view('user/std_footer', $data, true);
		
		$value = array(
			'std_header'	=>	$header,
			'std_top'		=>	$top,
			'std_left'			=>	$left,
			'content'		=>	$content,
			'std_footer'	=>	$footer
		);
		$this->load->view('user/std_frame', $value);
	}
	
	public function renderServiceCenter($pageName = null, $data = null)
	{
		$header = $this->load->view('service/std_header', $data, true);
		$top = $this->load->view('service/std_top', $data, true);
		$left = $this->load->view('service/std_left', $data, true);
		$content = $this->load->view("service/{$pageName}_view", $data, true);
		$footer = $this->load->view('service/std_footer', $data, true);
		
		$value = array(
			'std_header'	=>	$header,
			'std_top'		=>	$top,
			'std_left'			=>	$left,
			'content'		=>	$content,
			'std_footer'	=>	$footer
		);
		$this->load->view('service/std_frame', $value);
	}
}
?>