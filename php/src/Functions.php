<?php

declare( strict_types = 1 );

namespace App;

use Exception;
use Laminas\Stdlib\ArrayUtils;
use Laminas\Validator\StringLength;

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

	/**
	 * @param string $password
	 *
	 * @throws Exception
	 */
	public static function assertPasswordConstraints( string $password ) {
		$StringLength = ( new StringLength( [ 'min' => 8, 'max' => 100 ] ) )
			->setMessage( 'The password is less than %min% characters long!', StringLength::TOO_SHORT )
			->setMessage( 'The password is more than %max% characters long!', StringLength::TOO_LONG );
		if( !$StringLength->isValid( $password ) ) {
			$errors = [];
			foreach( $StringLength->getMessages() as $error ) {
				$errors[] = $error;
			}

			throw new Exception( implode( '', $errors ) );
		}
	}
}