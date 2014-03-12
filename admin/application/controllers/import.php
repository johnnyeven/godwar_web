<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
class Import extends CI_Controller
{

	public function __construct()
	{
		parent::__construct ();
	}
	
	public function index()
	{
		$this->load->view('import');
	}
	
	public function base_config_submit()
	{
		$this->load->library('Excel_BaseConfig_Adapter');
		
		$uploadDir = $this->config->item('upload_dir');
		$error = "";
		$msg = "";
		$fileElementName = 'baseConfig';
		$el = $this->input->get('el', TRUE);
		if($el) {
			$fileElementName = $el;
		}
		
		$config['upload_path'] = $uploadDir;
		$config['allowed_types'] = 'xls|xlsx';
		$config['encrypt_name'] = TRUE;
		
		$this->load->library('upload', $config);
		if (!$this->upload->do_upload($fileElementName)) {
			$error = $this->upload->display_errors('<stronng>', '</stronng>');
			echo $error;
		} else {
			$data = $this->upload->data();
			$fileName = $uploadDir . '/' . $data['file_name'];
		}
		if(!empty($fileName))
		{
			$result = $this->excel_baseconfig_adapter->ParseExcel($fileName);
			$result = $this->excel_baseconfig_adapter->RemoveNull($result);
			var_dump($result);
			if(!empty($result))
			{
				$this->load->library('Mongo_db');
				$this->mongo_db->drop_collection('godwar', 'base');
				$this->mongo_db->batch_insert('base', $result);
		
				var_dump($this->mongo_db->get('base'));
			}
		}
	}
	
	public function race_config_submit()
	{
		$this->load->library('Excel_RaceConfig_Adapter');
		
		$uploadDir = $this->config->item('upload_dir');
		$error = "";
		$msg = "";
		$fileElementName = 'raceConfig';
		$el = $this->input->get('el', TRUE);
		if($el) {
			$fileElementName = $el;
		}
		
		$config['upload_path'] = $uploadDir;
		$config['allowed_types'] = 'xls|xlsx';
		$config['encrypt_name'] = TRUE;
		
		$this->load->library('upload', $config);
		if (!$this->upload->do_upload($fileElementName)) {
			$error = $this->upload->display_errors('<stronng>', '</stronng>');
			echo $error;
		} else {
			$data = $this->upload->data();
			$fileName = $uploadDir . '/' . $data['file_name'];
		}
		
		if(!empty($fileName))
		{
			$result = $this->excel_raceconfig_adapter->ParseExcel($fileName);
			$result = $this->excel_raceconfig_adapter->RemoveNull($result);
			
			if(!empty($result))
			{
				$this->load->library('Mongo_db');
				$this->mongo_db->drop_collection('godwar', 'race');
				$this->mongo_db->batch_insert('race', $result);
		
				var_dump($this->mongo_db->get('race'));
			}
		}
	}
	
	public function exp_config_submit()
	{
		$this->load->library('Excel_ExpConfig_Adapter');
		
		$uploadDir = $this->config->item('upload_dir');
		$error = "";
		$msg = "";
		$fileElementName = 'expConfig';
		$el = $this->input->get('el', TRUE);
		if($el) {
			$fileElementName = $el;
		}
		
		$config['upload_path'] = $uploadDir;
		$config['allowed_types'] = 'xls|xlsx';
		$config['encrypt_name'] = TRUE;
		
		$this->load->library('upload', $config);
		if (!$this->upload->do_upload($fileElementName)) {
			$error = $this->upload->display_errors('<stronng>', '</stronng>');
			echo $error;
		} else {
			$data = $this->upload->data();
			$fileName = $uploadDir . '/' . $data['file_name'];
		}
		
		if(!empty($fileName))
		{
			$result = $this->excel_expconfig_adapter->ParseExcel($fileName);
			$result = $this->excel_expconfig_adapter->RemoveNull($result);
			
			if(!empty($result))
			{
				$this->load->library('Mongo_db');
				$this->mongo_db->drop_collection('godwar', 'exp');
				$this->mongo_db->batch_insert('exp', $result);
				$this->mongo_db->add_index('exp', array('level' => 'asc'), array('unique' => TRUE));
		
				var_dump($this->mongo_db->get('exp'));
			}
		}
	}
	
	public function monster_config_submit()
	{
		$this->load->library('Excel_MonsterConfig_Adapter');
		
		$uploadDir = $this->config->item('upload_dir');
		$error = "";
		$msg = "";
		$fileElementName = 'monsterConfig';
		$el = $this->input->get('el', TRUE);
		if($el) {
			$fileElementName = $el;
		}
		
		$config['upload_path'] = $uploadDir;
		$config['allowed_types'] = 'xls|xlsx';
		$config['encrypt_name'] = TRUE;
		
		$this->load->library('upload', $config);
		if (!$this->upload->do_upload($fileElementName)) {
			$error = $this->upload->display_errors('<stronng>', '</stronng>');
			echo $error;
		} else {
			$data = $this->upload->data();
			$fileName = $uploadDir . '/' . $data['file_name'];
		}
		
		if(!empty($fileName))
		{
			$result = $this->excel_monsterconfig_adapter->ParseExcel($fileName);
			$result = $this->excel_monsterconfig_adapter->RemoveNull($result);
			
			if(!empty($result))
			{
				$this->load->library('Mongo_db');
				$this->mongo_db->drop_collection('godwar', 'monster');
				$this->mongo_db->batch_insert('monster', $result);
				$this->mongo_db->add_index('monster', array('id' => 'asc'), array('unique' => TRUE));
				$this->mongo_db->add_index('monster', array('level' => 'desc'));
				var_dump($this->mongo_db->get('monster'));
			}
		}
	}
	
	public function map_config_submit()
	{
		$this->load->library('Excel_MapConfig_Adapter');
		
		$uploadDir = $this->config->item('upload_dir');
		$error = "";
		$msg = "";
		$fileElementName = 'mapConfig';
		$el = $this->input->get('el', TRUE);
		if($el) {
			$fileElementName = $el;
		}
		
		$config['upload_path'] = $uploadDir;
		$config['allowed_types'] = 'xls|xlsx';
		$config['encrypt_name'] = TRUE;
		
		$this->load->library('upload', $config);
		if (!$this->upload->do_upload($fileElementName)) {
			$error = $this->upload->display_errors('<stronng>', '</stronng>');
			echo $error;
		} else {
			$data = $this->upload->data();
			$fileName = $uploadDir . '/' . $data['file_name'];
		}
		
		if(!empty($fileName))
		{
			$result = $this->excel_mapconfig_adapter->ParseExcel($fileName);
			$result = $this->excel_mapconfig_adapter->RemoveNull($result);
			
			if(!empty($result))
			{
				$this->load->library('Mongo_db');
				$this->mongo_db->drop_collection('godwar', 'map');
				$this->mongo_db->batch_insert('map', $result);
				$this->mongo_db->add_index('map', array('id' => 'asc'), array('unique' => TRUE));
		
				var_dump($this->mongo_db->get('map'));
			}
		}
	}

	public function id_config_submit()
	{
		$this->load->library('Excel_IdConfig_Adapter');
		
		$uploadDir = $this->config->item('upload_dir');
		$error = "";
		$msg = "";
		$fileElementName = 'idConfig';
		$el = $this->input->get('el', TRUE);
		if($el) {
			$fileElementName = $el;
		}
		
		$config['upload_path'] = $uploadDir;
		$config['allowed_types'] = 'xls|xlsx';
		$config['encrypt_name'] = TRUE;
		
		$this->load->library('upload', $config);
		if (!$this->upload->do_upload($fileElementName)) {
			$error = $this->upload->display_errors('<stronng>', '</stronng>');
			echo $error;
		} else {
			$data = $this->upload->data();
			$fileName = $uploadDir . '/' . $data['file_name'];
		}
		
		if(!empty($fileName))
		{
			$result = $this->excel_idconfig_adapter->ParseExcel($fileName);
			
			if(!empty($result))
			{
				var_dump($result);
			}
		}
	}
}

?>