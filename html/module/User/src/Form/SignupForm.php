<?php

namespace User\Form;

use User\Enum\UserFields as UFs;
use Laminas\Form\Element\Csrf;

class SignupForm extends AbstractForm {
	public function __construct() {
		parent::__construct( 'signup' );

		$this->add( [
			'name' => UFs::ID,
			'type' => 'hidden',
		] );
		$this->add( [
			'name'    => UFs::USERNAME,
			'type'    => 'text',
			'options' => [
				'label' => 'Username:',
			],
		] );
		$this->add( [
			'name'    => UFs::PASSWORD,
			'type'    => 'password',
			'options' => [
				'label' => 'Password:',
			],
		] );
		$this->add( [
			'name'    => 'verify-password',
			'type'    => 'password',
			'options' => [
				'label' => 'Verify Password:',
			],
		] );
		$this->add( new Csrf( 'security' ) );
		$this->add( [
			'name'       => 'signup',
			'type'       => 'submit',
			'attributes' => [
				'value' => 'Sign Up',
				'id'    => 'signupButton',
			],
		] );
	}
}