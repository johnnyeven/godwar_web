<?php

class Excel_MonsterConfig_Adapter {
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
					'name'				=>	$objPHPExcel->getActiveSheet()->getCell("B$j")->getValue(),
					'level'				=>	intval($objPHPExcel->getActiveSheet()->getCell("C$j")->getValue()),
					'comment'			=>	$objPHPExcel->getActiveSheet()->getCell("D$j")->getValue(),
					'atk'				=>	intval($objPHPExcel->getActiveSheet()->getCell("E$j")->getValue()),
					'def'				=>	intval($objPHPExcel->getActiveSheet()->getCell("F$j")->getValue()),
					'mdef'				=>	intval($objPHPExcel->getActiveSheet()->getCell("G$j")->getValue()),
					'hit'				=>	intval($objPHPExcel->getActiveSheet()->getCell("H$j")->getValue()),
					'flee'				=>	intval($objPHPExcel->getActiveSheet()->getCell("I$j")->getValue()),
					'health'			=>	intval($objPHPExcel->getActiveSheet()->getCell("J$j")->getValue()),
					'skill_trigger'		=>	floatval($objPHPExcel->getActiveSheet()->getCell("K$j")->getValue()),
					'skill'				=>	json_decode($objPHPExcel->getActiveSheet()->getCell("L$j")->getValue()),
					'exp'				=>	intval($objPHPExcel->getActiveSheet()->getCell("M$j")->getValue()),
					'gold'				=>	intval($objPHPExcel->getActiveSheet()->getCell("N$j")->getValue()),
					'equipment_drop'	=>	floatval($objPHPExcel->getActiveSheet()->getCell("O$j")->getValue()),
					'blue_drop'			=>	floatval($objPHPExcel->getActiveSheet()->getCell("P$j")->getValue()),
					'green_drop'		=>	floatval($objPHPExcel->getActiveSheet()->getCell("Q$j")->getValue()),
					'purple_drop'		=>	floatval($objPHPExcel->getActiveSheet()->getCell("R$j")->getValue()),
					'gold_drop'			=>	floatval($objPHPExcel->getActiveSheet()->getCell("S$j")->getValue()),
					'equipments'		=>	json_decode($objPHPExcel->getActiveSheet()->getCell("T$j")->getValue()),
					'item_drop'			=>	floatval($objPHPExcel->getActiveSheet()->getCell("U$j")->getValue()),
					'items'				=>	json_decode($objPHPExcel->getActiveSheet()->getCell("V$j")->getValue()),
					'unique'			=>	json_decode($objPHPExcel->getActiveSheet()->getCell("W$j")->getValue())
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