<?php

class Excel_JobConfig_Adapter {
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
					'id'			=>	intval($sheet->getCell("A$j")->getValue()),
					'name'			=>	$sheet->getCell("B$j")->getValue(),
					'pre_job'		=>	intval($sheet->getCell("C$j")->getValue()),
					'level'			=>	intval($sheet->getCell("D$j")->getValue()),
					'comment'		=>	$sheet->getCell("E$j")->getValue(),
					'health_inc'	=>	floatval($sheet->getCell("F$j")->getValue()),
					'atk_inc'		=>	floatval($sheet->getCell("G$j")->getValue()),
					'def_inc'		=>	floatval($sheet->getCell("H$j")->getValue()),
					'mdef_inc'		=>	floatval($sheet->getCell("I$j")->getValue()),
					'hit_inc'		=>	floatval($sheet->getCell("J$j")->getValue()),
					'flee_inc'		=>	floatval($sheet->getCell("K$j")->getValue()),
					'health_add'	=>	intval($sheet->getCell("L$j")->getValue()),
					'atk_add'		=>	intval($sheet->getCell("M$j")->getValue()),
					'def_add'		=>	intval($sheet->getCell("N$j")->getValue()),
					'mdef_add'		=>	intval($sheet->getCell("O$j")->getValue()),
					'hit_add'		=>	intval($sheet->getCell("P$j")->getValue()),
					'flee_add'		=>	intval($sheet->getCell("Q$j")->getValue()),
					'skill'			=>	json_decode($sheet->getCell("R$j")->getValue())
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