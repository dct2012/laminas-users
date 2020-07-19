<?php

namespace User\Form;

use User\Model\User;
use Laminas\Form\Form;
use Laminas\Form\Element\Csrf;
use Laminas\Hydrator\ReflectionHydrator;

class LoginForm extends Form {
	public function __construct() {
		parent::__construct( 'login' );

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
		$this->add( new Csrf( 'security' ) );
		$this->add(
			[
				'name'       => 'login',
				'type'       => 'submit',
				'attributes' => [
					'value' => 'Login',
					'id'    => 'loginButton',
				],
			]
		);
	}
}