<?php

// This is a demonstration how to work with PhpWsdl without WSDL definitions in comments.
// More code, less comments. For whoever needs it... You may also mix definitions in 
// comments AND in this way together.

// Include the demonstration classes
require_once('class.soapdemo.php');
require_once('class.complextypedemo.php');

// Initialize the PhpWsdl class
require_once('class.phpwsdl.php');
PhpWsdlMethod::$DefaultException='SoapFault';// This will set SoapFault as exception type for all methods
$soap=PhpWsdl::CreateInstance(
	null,								// PhpWsdl will determine a good namespace
	null,								// Change this to your SOAP endpoint URI (or keep it NULL and PhpWsdl will determine it)
	'./cache',							// Change this to a folder with write access
	null,								// PhpWsdl should not parse PHP comments for this demonstration
	'SoapDemo',							// The name of the class that serves the webservice
	Array(								// Add methods
		new PhpWsdlMethod(				// SoapDemo->GetComplexType method
			'GetComplexType',
			null,
			new PhpWsdlParam(
				'GetComplexTypeResult',
				'ComplexTypeDemoB'
			)
		),
		new PhpWsdlMethod(				// SoapDemo->PrintComplexType method
			'PrintComplexType',
			Array(
				new PhpWsdlParam(
					'obj',
					'ComplexTypeDemoB'
				)
			),
			new PhpWsdlParam(
				'PrintComplexTypeResult',
				'string'
			)
		),
		new PhpWsdlMethod(				// SoapDemo->ComplexTypeArrayDemo method
			'ComplexTypeArrayDemo',
			Array(
				new PhpWsdlParam(
					'arr',
					'ComplexTypeDemoBArray'
				)
			),
			new PhpWsdlParam(
				'return',
				'stringArray'
			)
		),
		new PhpWsdlMethod(				// SoapDemo->SayHello method
			'SayHello',
			Array(
				new PhpWsdlParam(
					'name',
					'string'
				)
			),
			new PhpWsdlParam(
				'return',
				'string'
			)
		),
		new PhpWsdlMethod(				// SoapDemo->DemoMethod method
			'DemoMethod'
		)
	),
	Array(								// Add complex types
		new PhpWsdlComplex(				// ComplexTypeDemo class
			'ComplexTypeDemo',
			Array(
				new PhpWsdlElement(
					'StringA',
					'string'
				),
				new PhpWsdlElement(
					'StringB',
					'string'
				),
				new PhpWsdlElement(
					'Integer',
					'int'
				),
				new PhpWsdlElement(
					'Boolean',
					'boolean'
				)
			)
		),
		new PhpWsdlComplex(				// ComplexTypeDemoB class
			'ComplexTypeDemoB',
			Array(
				new PhpWsdlElement(
					'AdditionalString',
					'string'
				)
			),
			Array(
				'inherit'		=>	'ComplexTypeDemo'
			)
		),
		new PhpWsdlComplex(				// string array
			'stringArray'
		),
		new PhpWsdlComplex(				// ComplexTypeDemo array
			'ComplexTypeDemoArray'
		),
		new PhpWsdlEnum(				// string enumeration
			'DemoEnum',
			'string',
			Array(
				'ValueA',
				'ValueB',
				'ValueC'
			)
		),
		new PhpWsdlComplex(				// SoapFault exception type
			'SoapFault',
			Array(
				new PhpWsdlElement(
					'message',
					'string'
				),
				new PhpWsdlElement(
					'code',
					'int'
				),
				new PhpWsdlElement(
					'file',
					'string'
				),
				new PhpWsdlElement(
					'line',
					'int'
				)
			)
		)
	),
	false,								// Don't send WSDL right now
	false);								// Don't start the SOAP server right now

// Disable caching for demonstration
ini_set('soap.wsdl_cache_enabled',0);	// Disable caching in PHP
PhpWsdl::$CacheTime=0;					// Disable caching in PhpWsdl

// Run the SOAP server
if($soap->IsWsdlRequested())
	$soap->Optimize=false;				// Don't optimize WSDL to send it human readable to the browser
$soap->RunServer();						// Finally, run the server
