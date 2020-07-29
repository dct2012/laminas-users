<?php

namespace User\Form;

use User\Model\User;
use Laminas\Form\Form;
use User\Enum\UserFields as UFs;
use User\Strategy\Hydrator\IdStrategy;
use Laminas\Hydrator\ReflectionHydrator;
use User\Strategy\Hydrator\PasswordStrategy;

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