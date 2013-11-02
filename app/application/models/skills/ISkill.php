<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

interface ISkill
{
	function execute(&$attacker, &$defender);
}

?>