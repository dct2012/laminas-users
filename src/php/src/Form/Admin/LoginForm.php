<?php

declare( strict_types = 1 );

namespace App\Form\Admin;

use Laminas\Form\Form;
use Laminas\Form\Element\Csrf;
use App\Model\Values\IdentityFields as IFs;

class LoginForm extends Form {
	public function __construct() {
		parent::__construct( 'admin_login' );

		$this->add( [
			'name'       => IFs::NAME,
			'type'       => 'text',
			'attributes' => [
				'id'           => IFs::NAME,
				'class'        => 'input',
				'required'     => 'required',
				'autocomplete' => 'username',
				'placeholder'  => 'Admin Name',
			],
			'options'    => [
				'label'            => 'Admin Name:',
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
				'required'     => 'required',
				'placeholder'  => 'Password',
				'autocomplete' => 'current-password',
				'class'        => 'input toggle-password',
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
				'value' => 'Login',
				'id'    => 'loginButton',
				'class' => 'button is-block is-info',
			],
		] );
	}
}