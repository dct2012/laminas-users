<?php

namespace User\Strategy\Hydrator;

use User\Model\User;
use Laminas\Hydrator\Strategy\StrategyInterface;

class IdStrategy implements StrategyInterface {
	public function extract( $id, ?object $object = null ) {
		return (int)$id;
	}

	public function hydrate( $id, ?array $data ) {
		return (int)$id;
	}
}