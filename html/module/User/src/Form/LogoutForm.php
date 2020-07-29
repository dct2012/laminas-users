<?php

namespace User\Form;

class LogoutForm extends AbstractForm {
	public function __construct() {
		parent::__construct( 'logout' );

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