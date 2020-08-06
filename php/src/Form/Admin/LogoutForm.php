<?php

declare( strict_types = 1 );

namespace App\Form\Admin;

use Laminas\Form\Form;

class LogoutForm extends Form {
	public function __construct() {
		parent::__construct( 'admin_logout' );

		$this->add( [
			'name'       => 'logout',
			'type'       => 'submit',
			'attributes' => [
				'value' => 'logout',
				'id'    => 'logoutButton',
			],
		] );
	}
}