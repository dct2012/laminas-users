<?php

declare( strict_types = 1 );

use Laminas\Mvc\Application;
use Laminas\Stdlib\ArrayUtils;

chdir( dirname( __DIR__ ) );

// Decline static file requests back to the PHP built-in webserver
if( php_sapi_name() === 'cli-server' ) {
	$path = realpath( __DIR__.parse_url( $_SERVER[ 'REQUEST_URI' ], PHP_URL_PATH ) );
	if( is_string( $path ) && __FILE__ !== $path && is_file( $path ) ) {
		return false;
	}
	unset( $path );
}

include __DIR__.'/../vendor/autoload.php';

if( !class_exists( Application::class ) ) {
	throw new RuntimeException( "Unable to load app.\n"
		."- Type `composer install` if you are developing locally.\n"
		."- Type `vagrant ssh -c 'composer install'` if you are using Vagrant.\n"
		."- Type `docker-compose run laminas composer install` if you are using Docker.\n" );
}

$appConfig = require __DIR__.'/../config/app.config.php';
if( file_exists( __DIR__.'/../config/development.config.php' ) ) {
	$appConfig = ArrayUtils::merge( $appConfig, require __DIR__.'/../config/development.config.php' );
}

Application::init( $appConfig )->run();
