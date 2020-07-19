<?php

namespace User\Form;

use Laminas\Form\Form;

class LogoutForm extends Form {
	public function __construct() {
		parent::__construct( 'logout' );

		$this->add(
			[
				'name'       => 'logout',
				'type'       => 'submit',
				'attributes' => [
					'value' => 'logout',
					'id'    => 'logoutButton',
				],
			]
		);
	}
}