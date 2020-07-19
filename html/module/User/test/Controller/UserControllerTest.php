<?php

declare( strict_types = 1 );

namespace UserTest\Controller;

use User\Controller\UserController;
use Laminas\Stdlib\ArrayUtils;
use Laminas\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class UserControllerTest extends AbstractHttpControllerTestCase {
	public function setUp(): void {
		$configOverrides = [];

		$this->setApplicationConfig(
			ArrayUtils::merge(
				include __DIR__.'/../../../../config/application.config.php',
				$configOverrides
			)
		);

		parent::setUp();
	}

	public function testInfoActionRedirectsToLogin() {
		$this->dispatch( '/user', 'GET' );
		$this->assertResponseStatusCode( 302 );
		$this->assertModuleName( 'user' );
		$this->assertControllerName( UserController::class );
		$this->assertControllerClass( 'UserController' );
		$this->assertMatchedRouteName( 'user' );
	}

}
