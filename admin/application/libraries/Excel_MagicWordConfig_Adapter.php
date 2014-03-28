<?php

class Excel_MagicWordConfig_Adapter {
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
			
			for($j=2; $j<=$highestRow; $j++)
			{
				$row = array(
					'id'				=>	intval($objPHPExcel->getActiveSheet()->getCell("A$j")->getValue()),
					'group'				=>	intval($objPHPExcel->getActiveSheet()->getCell("B$j")->getValue()),
					'type'				=>	intval($objPHPExcel->getActiveSheet()->getCell("C$j")->getValue()),
					'name'				=>	$objPHPExcel->getActiveSheet()->getCell("D$j")->getValue(),
					'level'				=>	intval($objPHPExcel->getActiveSheet()->getCell("E$j")->getValue()),
					'equipment_position'=>	json_decode($objPHPExcel->getActiveSheet()->getCell("F$j")->getValue()),
					'property'			=>	array(
						'atk'				=>	floatval($objPHPExcel->getActiveSheet()->getCell("G$j")->getValue()),
						'atk_unit'			=>	intval($objPHPExcel->getActiveSheet()->getCell("H$j")->getValue()),
						'def'				=>	floatval($objPHPExcel->getActiveSheet()->getCell("I$j")->getValue()),
						'def_unit'			=>	intval($objPHPExcel->getActiveSheet()->getCell("J$j")->getValue()),
						'mdef'				=>	floatval($objPHPExcel->getActiveSheet()->getCell("K$j")->getValue()),
						'mdef_unit'			=>	intval($objPHPExcel->getActiveSheet()->getCell("L$j")->getValue()),
						'health_max'		=>	floatval($objPHPExcel->getActiveSheet()->getCell("M$j")->getValue()),
						'health_max_unit'	=>	intval($objPHPExcel->getActiveSheet()->getCell("N$j")->getValue()),
						'hit'				=>	floatval($objPHPExcel->getActiveSheet()->getCell("O$j")->getValue()),
						'hit_unit'			=>	intval($objPHPExcel->getActiveSheet()->getCell("P$j")->getValue()),
						'flee'				=>	floatval($objPHPExcel->getActiveSheet()->getCell("Q$j")->getValue()),
						'flee_unit'			=>	intval($objPHPExcel->getActiveSheet()->getCell("R$j")->getValue())
					)
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