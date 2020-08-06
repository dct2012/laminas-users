<?php

declare( strict_types = 1 );

namespace App\Enum;

use ReflectionClass, Exception;

abstract class AbstractEnum {
	/** @return static */
	static public function getInstance(): self {
		return new static();
	}

	/** @return array */
	public function getArrayCopy(): array {
		try {
			return ( new ReflectionClass( get_called_class() ) )->getConstants();
		} catch( Exception $e ) {
			return [];
		}
	}
}