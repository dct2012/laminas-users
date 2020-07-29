<?php

namespace User\Strategy\Hydrator;

use User\Model\User;
use Laminas\Hydrator\Strategy\StrategyInterface;

class PasswordStrategy implements StrategyInterface {
	public function extract( $password = '********', ?object $object = null ) {
		return $password;
	}

	public function hydrate( $password, ?array $data ) {
		return $password;
	}
}