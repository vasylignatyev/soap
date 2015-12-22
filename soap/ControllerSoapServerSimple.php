<?php
	require_once ('../php-wsdl/class.phpwsdl.php');

	//require_once ('ControllerSoap.php');
	//require_once ('../controller/ControllerDataStruct.php');

	PhpWsdl::RunQuickMode ( Array ( 'ControllerSoap.php', '../controller/ControllerDataStruct.php' ) );