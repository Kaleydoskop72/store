<?php

switch ($currentConfigExport) {
	case CONFIG_EXPORT_PRO:
		return [
		    'class' => 'yii\db\Connection',
		    'dsn' => 'mysql:host=tkanirukodelie.ru;dbname=cb66116_testtkrk',
		    'username' => 'cb66116_testtkrk',
		    'password' => 'tQgPdmjZNkjo',
		    'charset' => 'utf8',
		];
		break;
	case CONFIG_EXPORT_DEV:
		return [
		    'class' => 'yii\db\Connection',
		    'dsn' => 'mysql:host=localhost;dbname=oc_dev15',
		    'username' => 'root',
		    'password' => 'Qwerty1234',
		    'charset' => 'utf8',
		];	
		break;
	case CONFIG_EXPORT_TEST:
		return [
		    'class' => 'yii\db\Connection',
		    'dsn' => 'mysql:host=matroskin;dbname=oc_test',
		    'username' => 'root',
		    'password' => 'Qwerty1234',
		    'charset' => 'utf8',
		];
		break;
}



