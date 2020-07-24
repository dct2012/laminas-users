<?php

namespace User\Form;

use Laminas\Form\Form;

class InfoForm extends Form {
	public function __construct() {
		parent::__construct( 'user' );

		$this->add(
			[
				'name'    => 'id',
				'type'    => 'text',
				'options' => [
					'label' => 'ID:',
				],
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
				'name'    => 'updated_on',
				'type'    => 'text',
				'options' => [
					'label' => 'Last Updated:',
				],
			]
		);
		$this->add(
			[
				'name'    => 'created_on',
				'type'    => 'text',
				'options' => [
					'label' => 'Created On:',
				],
			]
		);
	}
}