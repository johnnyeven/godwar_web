<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

interface IItem
{
	function execute(& $target, & $parameter);
}

?>