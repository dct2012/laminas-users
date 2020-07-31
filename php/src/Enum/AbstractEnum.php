<?php

declare( strict_types = 1 );

namespace App\Enum;

use ReflectionClass, ReflectionException;

abstract class AbstractEnum {
	/** @return static */
	static public function getInstance(): self {
		return new static();
	}

	/**
	 * @return array
	 * @throws ReflectionException
	 */
	public function getArrayCopy(): array {
		return ( new ReflectionClass( get_called_class() ) )->getConstants();
	}
}