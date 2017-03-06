<?php

switch ($currentConfig) {
	case CONFIG_DEV:
		return [
		    'class' => 'yii\db\Connection',
		    'dsn' => 'mysql:host=localhost;dbname=mngr_dev',
		    'username' => 'root',
		    'password' => 'Qwerty1234',
		    'charset' => 'utf8',
		];
		break;
	case CONFIG_PRO:
		return [
		    'class' => 'yii\db\Connection',
		    'dsn' => 'mysql:host=localhost;dbname=mngr',
		    'username' => 'root',
		    'password' => 'Qwerty1234',
		    'charset' => 'utf8',
		];
		break;
}
