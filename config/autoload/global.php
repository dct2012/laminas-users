<?php

/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

use Laminas\Session\Storage\SessionStorage;
use Laminas\Session\Validator\{HttpUserAgent, RemoteAddr};

return [
	'db'              => [
		'driver'   => 'Pdo',
		'dsn'      => 'mysql:dbname=docker;host=users-db;charset=utf8',
		'username' => 'docker',
		'password' => 'docker',
	],
	'session_manager' => [
		'validators' => [
			HttpUserAgent::class,
			RemoteAddr::class,
		],
	],
	'session_storage' => [
		'type' => SessionStorage::class,
	],
	'session_config'  => [
		'php_save_handler' => 'redis',
		'save_path'        => 'tcp://users-redis:6379',
	],
];
