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
			'name'    => IFs::NAME,
			'type'    => 'text',
			'options' => [
				'label' => 'Admin name:',
			],
		] );
		$this->add( [
			'name'    => IFs::PASSWORD,
			'type'    => 'password',
			'options' => [
				'label' => 'Password:',
			],
		] );
		$this->add( new Csrf( 'security' ) );
		$this->add( [
			'name'       => 'login',
			'type'       => 'submit',
			'attributes' => [
				'value' => 'Login',
				'id'    => 'loginButton',
			],
		] );
	}
}