<?php

declare( strict_types = 1 );

namespace App;

use Laminas\Mvc\MvcEvent;

class Module {
	/** @return array */
	public function getConfig(): array {
		return include __DIR__.'/../config/module.config.php';
	}
}
