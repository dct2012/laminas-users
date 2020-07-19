<?php

namespace User\Form;

use User\Model\User;
use Laminas\Form\Form;
use Laminas\Form\Element\Csrf;
use Laminas\Hydrator\ReflectionHydrator;

class SignupForm extends Form {
	public function __construct() {
		parent::__construct( 'signup' );

		$this->setHydrator( new ReflectionHydrator() );
		$this->setObject( new User( '', '' ) );

		$this->add(
			[
				'name' => 'id',
				'type' => 'hidden',
			]
		);
		$this->add(
			[
				'name'    => 'username',
				'type'    => 'text',
				'options' => [
					'label' => 'Username:',
				],
			]
		);
		$this->add(
			[
				'name'    => 'password',
				'type'    => 'password',
				'options' => [
					'label' => 'Password:',
				],
			]
		);
		$this->add(
			[
				'name'    => 'verify-password',
				'type'    => 'password',
				'options' => [
					'label' => 'Verify Password:',
				],
			]
		);
		$this->add( new Csrf( 'security' ) );
		$this->add(
			[
				'name'       => 'signup',
				'type'       => 'submit',
				'attributes' => [
					'value' => 'Sign Up',
					'id'    => 'signupButton',
				],
			]
		);
	}
}