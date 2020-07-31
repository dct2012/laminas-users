<?php

declare( strict_types = 1 );

namespace App\Form;

use Laminas\Form\Element\Csrf;
use App\Model\Values\UserFields as UFs;

class LoginForm extends AbstractForm {
	public function __construct() {
		parent::__construct( 'login' );

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