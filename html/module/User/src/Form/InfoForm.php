<?php

namespace User\Form;

use User\Enum\UserFields as UFs;

class InfoForm extends AbstractForm {
	public function __construct() {
		parent::__construct( 'user' );

		$this->add( [
			'name'    => UFs::ID,
			'type'    => 'number',
			'options' => [
				'label' => 'ID:',
			],
		] );
		$this->add( [
			'name'    => UFs::USERNAME,
			'type'    => 'text',
			'options' => [
				'label' => 'Username:',
			],
		] );
		$this->add( [
			'name'    => UFs::UPDATED_ON,
			'type'    => 'text',
			'options' => [
				'label' => 'Last Updated:',
			],
		] );
		$this->add( [
			'name'    => UFs::CREATED_ON,
			'type'    => 'text',
			'options' => [
				'label' => 'Created On:',
			],
		] );
	}
}