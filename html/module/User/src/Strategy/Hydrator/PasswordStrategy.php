<?php

namespace User\Strategy\Hydrator;

use Laminas\Hydrator\Strategy\StrategyInterface;

class PasswordStrategy implements StrategyInterface {
	public function extract( $password = '********', ?object $object = null ) {
		return $password;
	}

	public function hydrate( $password, ?array $data ) {
		return $password;
	}
}