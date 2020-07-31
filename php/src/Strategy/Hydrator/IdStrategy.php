<?php

declare( strict_types = 1 );

namespace App\Strategy\Hydrator;

use Laminas\Hydrator\Strategy\StrategyInterface;

class IdStrategy implements StrategyInterface {
	/**
	 * @param mixed   $id
	 * @param ?object $object
	 *
	 * @return int
	 */
	public function extract( $id, ?object $object = null ): int {
		return (int)$id;
	}

	/**
	 * @param mixed  $id
	 * @param ?array $data
	 *
	 * @return int
	 */
	public function hydrate( $id, ?array $data ): int {
		return (int)$id;
	}
}