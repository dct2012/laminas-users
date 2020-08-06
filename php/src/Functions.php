<?php

declare( strict_types = 1 );

namespace App;

class Functions {
	/** @return string */
	public static function createTimeStamp(): string {
		return date( 'Y-m-d h:i:s' );
	}
}