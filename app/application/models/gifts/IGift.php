<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

interface IGift
{
	function can_hooked($action);
	function execute(&$parameter);
}

?>