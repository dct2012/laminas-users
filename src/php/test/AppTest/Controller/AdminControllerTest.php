<?php

declare( strict_types = 1 );

namespace AppTest\Controller;

use App\Controller\IndexController;
use Laminas\Stdlib\ArrayUtils;
use Laminas\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class AdminControllerTest extends AbstractHttpControllerTestCase {
	public function setUp(): void {
		$configOverrides = [];

		$this->setApplicationConfig( ArrayUtils::merge(
			include __DIR__.'/../../../../../config/app.config.php',
			$configOverrides
		) );

		parent::setUp();
	}

	public function testAdminRedirectsWhenAccessed() {
		$this->dispatch( '/admin', 'GET' );
		$this->assertResponseStatusCode( 302 );
		$this->assertModuleName( 'app' );
		$this->assertControllerName( IndexController::class );
		$this->assertControllerClass( 'IndexController' );
		$this->assertMatchedRouteName( 'home' );
	}

	public function testInvalidRouteDoesNotCrash() {
		$this->dispatch( '/invalid/route', 'GET' );
		$this->assertResponseStatusCode( 404 );
	}
}
