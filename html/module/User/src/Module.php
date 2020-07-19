<?php

namespace User;

class Module {
	/** @return array */
	public function getConfig(): array {
		return include __DIR__.'/../config/module.config.php';
	}
}