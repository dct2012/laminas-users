<?php

declare( strict_types = 1 );

namespace App\Form;

use App\Model\User;
use App\Model\Values\UserFields as UFs;
use App\Strategy\Hydrator\{IdStrategy, PasswordStrategy};
use Laminas\Form\Form;
use Laminas\Hydrator\ReflectionHydrator;

abstract class AbstractForm extends Form {
	public function __construct( $name = null ) {
		parent::__construct( $name );

		$Hydrator = new ReflectionHydrator();
		$Hydrator->addStrategy( UFs::PASSWORD, new PasswordStrategy() );
		$Hydrator->addStrategy( UFs::ID, new IdStrategy() );

		$this->setHydrator( $Hydrator );
		$this->setObject( new User( '', '' ) );
	}
}