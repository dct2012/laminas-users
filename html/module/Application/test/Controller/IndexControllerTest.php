<?php

declare( strict_types = 1 );

namespace ApplicationTest\Controller;

use Laminas\Stdlib\ArrayUtils;
use Application\Controller\IndexController;
use Laminas\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class IndexControllerTest extends AbstractHttpControllerTestCase {
	public function setUp(): void {
		// The module configuration should still be applicable for tests.
		// You can override configuration here with test case specific values,
		// such as sample view templates, path stacks, module_listener_options,
		// etc.
		$configOverrides = [];

		$this->setApplicationConfig(
			ArrayUtils::merge(
				include __DIR__.'/../../../../config/application.config.php',
				$configOverrides
			)
		);

		parent::setUp();
	}

	public function testIndexActionRedirectsWhenAccessed() {
		$this->dispatch( '/', 'GET' );
		$this->assertResponseStatusCode( 302 );
		$this->assertModuleName( 'application' );
		$this->assertControllerName( IndexController::class );
		$this->assertControllerClass( 'IndexController' );
		$this->assertMatchedRouteName( 'home' );
	}

	public function testInvalidRouteDoesNotCrash() {
		$this->dispatch( '/invalid/route', 'GET' );
		$this->assertResponseStatusCode( 404 );
	}
}
