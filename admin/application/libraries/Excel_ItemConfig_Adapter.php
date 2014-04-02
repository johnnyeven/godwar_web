<?php

class Excel_ItemConfig_Adapter {
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
					'comment'		=>	$sheet->getCell("C$j")->getValue(),
					'type'			=>	intval($sheet->getCell("D$j")->getValue()),
					'remain_time'	=>	intval($sheet->getCell("E$j")->getValue()),
					'atk_inc'		=>	floatval($sheet->getCell("F$j")->getValue()),
					'atk_inc_unit'	=>	intval($sheet->getCell("G$j")->getValue()),
					'def_inc'		=>	floatval($sheet->getCell("H$j")->getValue()),
					'def_inc_unit'	=>	intval($sheet->getCell("I$j")->getValue()),
					'mdef_inc'		=>	floatval($sheet->getCell("J$j")->getValue()),
					'mdef_inc_unit'	=>	intval($sheet->getCell("K$j")->getValue()),
					'health_max_inc'=>	floatval($sheet->getCell("L$j")->getValue()),
					'health_max_inc_unit'	=>	intval($sheet->getCell("M$j")->getValue()),
					'hit_inc'		=>	floatval($sheet->getCell("N$j")->getValue()),
					'hit_inc_unit'	=>	intval($sheet->getCell("O$j")->getValue()),
					'crit_inc'		=>	floatval($sheet->getCell("P$j")->getValue()),
					'crit_inc_unit'	=>	intval($sheet->getCell("Q$j")->getValue()),
					'flee_inc'		=>	floatval($sheet->getCell("R$j")->getValue()),
					'flee_inc_unit'	=>	intval($sheet->getCell("S$j")->getValue()),
					'exp_inc'		=>	floatval($sheet->getCell("T$j")->getValue()),
					'exp_inc_unit'	=>	intval($sheet->getCell("U$j")->getValue()),
					'gold_inc'		=>	floatval($sheet->getCell("V$j")->getValue()),
					'gold_inc_unit'	=>	intval($sheet->getCell("W$j")->getValue()),
					'vitality_inc'	=>	floatval($sheet->getCell("X$j")->getValue()),
					'vitality_inc_unit'	=>	intval($sheet->getCell("Y$j")->getValue()),
					'price'			=>	intval($sheet->getCell("Z$j")->getValue())
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