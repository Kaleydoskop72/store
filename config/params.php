<?php

define("CONFIG_DEV", 1);
define("CONFIG_PRO", 2);

define("CONFIG_EXPORT_PRO",  3);
define("CONFIG_EXPORT_DEV",  4);
define("CONFIG_EXPORT_TEST", 5);

$currentConfig = CONFIG_DEV;
$currentConfigExport = CONFIG_EXPORT_DEV;


switch ($currentConfig) {
	case CONFIG_DEV:
		$siteName = "ТЕСТОВАЯ БАЗА (CC)";
		$mngrDir = "/home/z/www/mngr-dev.oc.ru/html/";
		$dirImport = $mngrDir.'../import';
		break;
	case CONFIG_PRO:
		$siteName = "РАБОЧАЯ БАЗА (M)";
		$mngrDir = "/home/z/www/mngr-pro.oc.ru/html/";	
		$dirImport = $mngrDir.'../import';		
		break;
}
switch ($currentConfigExport) {
	case CONFIG_EXPORT_PRO:
		$ocDir   = "/home/c/cb66116/tkanirukodelie.ru/public_html/";
		$isOcSiteFTP = 1;
		$ftpServer = 'tkanirukodelie.ru';
		$ftpUser = 'cb66116_ftp';
		$ftpPassword = 'tQgPdmjZNkjo';
		break;
	case CONFIG_EXPORT_DEV:
		$ocDir   = "/home/z/www/dev15.oc.ru/html/";
		$isOcSiteFTP = 0;
		$ftpServer = '';
		$ftpUser = '';
		$ftpPassword = '';
		break;
	case CONFIG_EXPORT_TEST:
		$ocDir   = "/home/z/www/tkanirukodelie-test.oc.ru/html/";
		$isOcSiteFTP = 1;
		$ftpServer = 'tkanirukodelie-test.oc.ru';		
		$ftpUser = 'z';
		$ftpPassword = 'Qwerty1234';
		break;
}


return [
	'siteName'	 => $siteName,
    'adminEmail' => 'admin@example.com',
    'dirImage' 	 => $mngrDir.'/web/images/',
    'dirImageColors' 	   => $mngrDir.'/web/images/colors',
    'dirImageManufacturer' => $mngrDir.'/web/imagesManufacturer/',
	'ocDirImage' 	   	   => $ocDir.'/image/data/product',	
	'ocDirImageColors' 	   => $ocDir.'/image/data/product/colors',
	'dirTmp' 			   => $mngrDir.'/../tmp',
	'dirImport'				=> $dirImport,
	//'ocDirImage' => '/hosting/tkanirukodelie.ru/public_html/image/data/product/',
	'ftpServer'   => $ftpServer,	
	'ftpUser'     => $ftpUser,
	'ftpPassword' => $ftpPassword,
	'isOcSiteFTP' => $isOcSiteFTP
];


// return [
// 	'siteName'	=> 'mngr-dev Test',
//     'adminEmail' => 'admin@example.com',
//     'dirImage' 	=> '/home/z/www/mngr-dev.oc.ru/html/web/images/',  
//     'dirImageColors' 	   => '/home/z/www/mngr-dev.oc.ru/html/web/images/colors',    
//     'dirImageManufacturer' => '/home/z/www/mngr-dev.oc.ru/html/web/imagesManufacturer/',
// 	'ocDirImage' => '/home/z/www/dev15.oc.ru/html/image/data/product/',	
// 	'ocDirImageColors' => '/home/z/www/dev15.oc.ru/html/image/data/colors',
// 	'dirImport' => '/home/z/www/mngr-dev.oc.ru/import',
// 	'dirTmp' => '/home/z/www/mngr-dev.oc.ru/tmp',
// 	//'ocDirImage' => '/hosting/tkanirukodelie.ru/public_html/image/data/product/',
// 	'ftpUser'     => 'cb66116_ftp',
// 	'ftpPassword' => 'tQgPdmjZNkjo',
// 	'isOcSiteFTP' => 1
// ];

