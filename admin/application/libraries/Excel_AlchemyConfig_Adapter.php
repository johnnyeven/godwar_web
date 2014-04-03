<?php

class Excel_AlchemyConfig_Adapter {
	private $CI;

	public function ParseExcel($file)
	{
		set_include_path(get_include_path() . PATH_SEPARATOR . BASEPATH . 'libraries/excel');
	
		include_once 'PHPExcel.php';
		include_once 'PHPExcel/IOFactory.php';
		include_once 'PHPExcel/Reader/Excel5.php';

		$result = array();
		if(!empty($file)) //如果上传文件成功，就执行导入excel操作
		{
			$objReader = PHPExcel_IOFactory::createReader('Excel5');//use excel2007 for 2007 format
			$objPHPExcel = $objReader->load($file);
			$sheet = $objPHPExcel->getSheet(0);
			$highestRow = $sheet->getHighestRow(); // 取得总行数
			$highestColumn = $sheet->getHighestColumn(); // 取得总列数

			$this->CI =& get_instance();
			$this->CI->load->library('Mongo_db');
			for($j=2; $j<=$highestRow; $j++)
			{
				$materials = json_decode($objPHPExcel->getActiveSheet()->getCell("C$j")->getValue(), TRUE);
				foreach($materials as $key=>$value)
				{
					$parameter = array(
						'id'	=>	$value['id']
					);
					$m = $this->CI->mongo_db->where($parameter)->get('item');
					$m = $m[0];

					$materials[$key]['name'] = $m['name'];
					$materials[$key]['comment'] = $m['comment'];
					$materials[$key]['type'] = $m['type'];
				}
				$row = array(
					'id'			=>	intval($objPHPExcel->getActiveSheet()->getCell("A$j")->getValue()),
					'name'			=>	$objPHPExcel->getActiveSheet()->getCell("B$j")->getValue(),
					'materials'		=>	$materials
				);
				array_push($result, $row);
			}
			unlink($file);
		}
		return $result;
	}
	
	public function RemoveNull($array)
	{
		for($i=0; $i<count($array); $i++)
		{
			foreach($array[$i] as $key=>$value)
			{
				if(empty($value))
				{
					$array[$i][$key] = '';
				}
			}
		}
		return $array;
	}
}

?>