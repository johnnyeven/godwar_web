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
		$content = $this->load->view($pageName, $data, true);
		$footer = $this->load->view('std_bottom', $data, true);
		
		$value = array(
			'std_header'	=>	$header,
			'std_top'		=>	$top,
			'std_main'		=>	$content,
			'std_bottom'	=>	$footer
		);
		$this->load->view('std_frame', $value);
	}
}
?>