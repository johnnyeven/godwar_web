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
			// $result = $this->excel_baseconfig_adapter->RemoveNull($result);
			if(!empty($result))
			{
				$this->load->model('base_config');
				foreach ($result as $key => $value) {
                    $this->base_config->create($value);
                }
				var_dump($this->base_config->read());
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
			// $result = $this->excel_raceconfig_adapter->RemoveNull($result);
			if(!empty($result))
			{
                $this->load->model('race_config');
                foreach ($result as $key => $value) {
                    $this->race_config->create($value);
                }
                var_dump($this->race_config->read());
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
			// $result = $this->excel_expconfig_adapter->RemoveNull($result);
			if(!empty($result))
			{
                $this->load->model('exp_config');
                foreach ($result as $key => $value) {
                    $this->exp_config->create($value);
                }
                var_dump($this->exp_config->read());
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
			// $result = $this->excel_monsterconfig_adapter->RemoveNull($result);
			if(!empty($result))
			{
                $this->load->model('monster_config');
                foreach ($result as $key => $value) {
                    $this->monster_config->create($value);
                }
                var_dump($this->monster_config->read());
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
			// $result = $this->excel_mapconfig_adapter->RemoveNull($result);
			if(!empty($result))
			{
                $this->load->model('map_config');
                foreach ($result as $key => $value) {
                    $this->map_config->create($value);
                }
                var_dump($this->map_config->read());
			}
		}
	}
	
	public function job_config_submit()
	{
		$this->load->library('Excel_JobConfig_Adapter');
		
		$uploadDir = $this->config->item('upload_dir');
		$error = "";
		$msg = "";
		$fileElementName = 'jobConfig';
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
			$result = $this->excel_jobconfig_adapter->ParseExcel($fileName);
			// $result = $this->excel_jobconfig_adapter->RemoveNull($result);
			if(!empty($result))
			{
                $this->load->model('job_config');
                foreach ($result as $key => $value) {
                    $this->job_config->create($value);
                }
                var_dump($this->job_config->read());
			}
		}
	}
	
	public function item_config_submit()
	{
		$this->load->library('Excel_ItemConfig_Adapter');
		
		$uploadDir = $this->config->item('upload_dir');
		$error = "";
		$msg = "";
		$fileElementName = 'itemConfig';
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
			$result = $this->excel_itemconfig_adapter->ParseExcel($fileName);
			// $result = $this->excel_itemconfig_adapter->RemoveNull($result);
            var_dump($result);
			if(!empty($result))
			{
                $this->load->model('item_config');
                foreach ($result as $key => $value) {
                    $this->item_config->create($value);
                }
                var_dump($this->item_config->read());
			}
		}
	}
	
	public function equipment_config_submit()
	{
		$this->load->library('Excel_EquipmentConfig_Adapter');
		
		$uploadDir = $this->config->item('upload_dir');
		$error = "";
		$msg = "";
		$fileElementName = 'equipmentConfig';
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
			$result = $this->excel_equipmentconfig_adapter->ParseExcel($fileName);
			// $result = $this->excel_equipmentconfig_adapter->RemoveNull($result);
			if(!empty($result))
			{
                $this->load->model('equipment_config');
                foreach ($result as $key => $value) {
                    $this->equipment_config->create($value);
                }
                var_dump($this->equipment_config->read());
			}
		}
	}
	
	public function magicword_config_submit()
	{
		$this->load->library('Excel_MagicWordConfig_Adapter');
		
		$uploadDir = $this->config->item('upload_dir');
		$error = "";
		$msg = "";
		$fileElementName = 'magicWordConfig';
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
			$result = $this->excel_magicwordconfig_adapter->ParseExcel($fileName);
			// $result = $this->excel_magicwordconfig_adapter->RemoveNull($result);
			if(!empty($result))
			{
                $this->load->model('magic_word_config');
                foreach ($result as $key => $value) {
                    $this->magic_word_config->create($value);
                }
                var_dump($this->magic_word_config->read());
			}
		}
	}
	
	public function alchemy_config_submit()
	{
		$this->load->library('Excel_AlchemyConfig_Adapter');
		
		$uploadDir = $this->config->item('upload_dir');
		$error = "";
		$msg = "";
		$fileElementName = 'alchemyConfig';
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
			$result = $this->excel_alchemyconfig_adapter->ParseExcel($fileName);
			// $result = $this->excel_magicwordconfig_adapter->RemoveNull($result);
			if(!empty($result))
			{
                $this->load->model('alchemy_config');
                foreach ($result as $key => $value) {
                    $this->alchemy_config->create($value);
                }
                var_dump($this->alchemy_config->read());
			}
		}
	}

	public function const_config_submit()
	{
		$this->load->library('Excel_ConstConfig_Adapter');
		
		$uploadDir = $this->config->item('upload_dir');
		$error = "";
		$msg = "";
		$fileElementName = 'constConfig';
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
			$result = $this->excel_constconfig_adapter->ParseExcel($fileName);
			
			if(!empty($result))
			{
				//创建../app/application/config/const.config.php
				$file = fopen('../app/application/config/const.config.php', 'w');
				if($file)
				{
					$const_race = var_export($result['race'], TRUE);
					$const_job = var_export($result['job'], TRUE);
					
					$config = "<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

\$config['const_race'] = {$const_race};
\$config['const_job'] = {$const_job};

?>";
					
					if(!fwrite($file, $config))
					{
						echo '写入必要文件失败/app/application/config/const.config.php，请检查是否具备读写权限';
					}
					fclose($file);
				}
				else
				{
					exit('创建必要文件失败/app/application/config/const.config.php，请检查是否具备读写权限');
				}
			}
		}
	}
}

?>