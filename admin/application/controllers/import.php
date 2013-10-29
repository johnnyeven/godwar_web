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
	
	public function submit()
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
}

?>