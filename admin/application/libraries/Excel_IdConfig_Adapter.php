<?php

class Excel_IdConfig_Adapter {
	public function ParseExcel($file)
	{
		set_include_path(get_include_path() . PATH_SEPARATOR . BASEPATH . 'libraries/excel');
	
		include_once 'PHPExcel.php';
		include_once 'PHPExcel/IOFactory.php';
		include_once 'PHPExcel/Reader/Excel5.php';

		$resultRace = array();
		$resultJob = array();
		$isJob = false;
		if(!empty($file)) //如果上传文件成功，就执行导入excel操作
		{
			$objReader = PHPExcel_IOFactory::createReader('Excel5');//use excel2007 for 2007 format
			$objPHPExcel = $objReader->load($file);
			$sheet = $objPHPExcel->getSheet(0);
			$highestRow = $sheet->getHighestRow(); // 取得总行数
			$highestColumn = $sheet->getHighestColumn(); // 取得总列数
			
			for($j=1; $j<=$highestRow; $j++)
			{
				$row = array(
					'key'			=>	intval($objPHPExcel->getActiveSheet()->getCell("A$j")->getValue()),
					'value'			=>	intval($objPHPExcel->getActiveSheet()->getCell("B$j")->getValue()),
				);
				if(!$isJob)
				{
					if(empty($row['key']))
					{
						$isJob = true;
						continue;
					}
					array_push($resultRace, $row);
				}
				else
				{
					array_push($resultJob, $row);
				}
			}
			unlink($file);
		}
		return $result;
	}
}

?>