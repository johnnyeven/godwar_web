<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('rate_random_element'))
{
	function rate_random_element($array)
	{
		if ( ! is_array($array))
		{
			return $array;
		}

		if(count($array) == 1)
		{
			return $array[0]['id'];
		}

		$max_rate = 1000;

		foreach($array as $row)
		{
			// if($row['rate'] < 10)
			// {
			// 	$max_rate += $row['rate'] * 100;
			// }
			// else
			// {
			// 	$max_rate += $row['rate'];
			// }
			$rand = rand(0, $max_rate);
			$rate = $row['rate'] * 1000;

			if($rand > $rate)
			{
				$max_rate -= $rate;
			}
			else
			{
				return $row['id'];
			}
		}

		return null;
	}
}

?>