<?php

declare( strict_types = 1 );

namespace App\Form\User;

use Laminas\Form\Form;
use Laminas\Form\Element\Csrf;
use App\Model\Values\IdentityFields as IFs;

class LoginForm extends Form {
	public function __construct() {
		parent::__construct( 'user_login' );

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
				'autocomplete' => 'current-password',
			],
			'options'    => [
				'label'            => 'Password:',
				'label_attributes' => [
					'class' => 'label',
				],
			],
		] );
		$this->add( new Csrf( 'security' ) );
		$this->add( [
			'name'       => 'login',
			'type'       => 'submit',
			'attributes' => [
				'id'    => 'loginButton',
				'value' => 'Login',
				'class' => 'button is-block is-info',
			],
		] );
	}
}