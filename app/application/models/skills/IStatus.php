<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

interface IStatus
{
	function execute(& $target, & $parameter);
	function destroy(& $target);
}

?>