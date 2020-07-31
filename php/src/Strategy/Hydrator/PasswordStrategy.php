<?php

declare( strict_types = 1 );

namespace App\Strategy\Hydrator;

use Laminas\Hydrator\Strategy\StrategyInterface;

class PasswordStrategy implements StrategyInterface {
	/**
	 * @param string      $password
	 * @param object|null $object
	 *
	 * @return string
	 */
	public function extract( $password = '********', ?object $object = null ): string {
		return (string)$password;
	}

	/**
	 * @param mixed  $password
	 * @param ?array $data
	 *
	 * @return mixed
	 */
	public function hydrate( $password, ?array $data ): string {
		return (string)$password;
	}
}