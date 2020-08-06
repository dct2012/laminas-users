<?php

declare( strict_types = 1 );

namespace App;

use Laminas\Stdlib\ArrayUtils;

class Functions {
	/** @return string */
	public static function createTimeStamp(): string {
		return date( 'Y-m-d h:i:s' );
	}

	/** @return array */
	public static function getConfig(): array {
		$appConfig = require __DIR__.'/../../config/app.config.php';
		if( file_exists( __DIR__.'/../../config/development.config.php' ) ) {
			$appConfig = ArrayUtils::merge( $appConfig, require __DIR__.'/../../config/development.config.php' );
		}

		return $appConfig;
	}

	/** @return array */
	public static function getDbConfig(): array {
		$config = require __DIR__.'/../../config/autoload/global.php';

		return $config[ 'db' ];
	}
}