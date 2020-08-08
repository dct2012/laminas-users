<?php

declare( strict_types = 1 );

namespace App\Form\User;

use Laminas\Form\Form;
use Laminas\Form\Element\Csrf;
use App\Model\Values\IdentityFields as IFs;

class SignupForm extends Form {
	const VERIFY_PASSWORD = 'verify-password';

	public function __construct() {
		parent::__construct( 'user_signup' );

		$this->add( [
			'name'       => IFs::NAME,
			'type'       => 'text',
			'attributes' => [
				'id'           => IFs::NAME,
				'class'        => 'input',
				'required'     => 'required',
				'placeholder'  => 'Username',
				'autocomplete' => 'username',
			],
			'options'    => [
				'label'            => 'Username:',
				'label_attributes' => [
					'class' => 'label',
				],
			],
		] );
		$this->add( [
			'name'       => IFs::PASSWORD,
			'type'       => 'password',
			'attributes' => [
				'id'           => IFs::PASSWORD,
				'class'        => 'input toggle-password',
				'required'     => 'required',
				'placeholder'  => 'Password',
				'autocomplete' => 'new-password',
			],
			'options'    => [
				'label'            => 'Password:',
				'label_attributes' => [
					'class' => 'label',
				],
			],
		] );
		$this->add( [
			'name'       => self::VERIFY_PASSWORD,
			'type'       => 'password',
			'attributes' => [
				'id'           => self::VERIFY_PASSWORD,
				'class'        => 'input toggle-password',
				'required'     => 'required',
				'placeholder'  => 'Password',
				'autocomplete' => 'new-password',
			],
			'options'    => [
				'label'            => 'Verify Password:',
				'label_attributes' => [
					'class' => 'label',
				],
			],
		] );
		$this->add( new Csrf( 'security' ) );
		$this->add( [
			'name'       => 'signup',
			'type'       => 'submit',
			'attributes' => [
				'value' => 'Sign Up',
				'id'    => 'signupButton',
				'class' => 'button is-block is-info',
			],
		] );
	}
}