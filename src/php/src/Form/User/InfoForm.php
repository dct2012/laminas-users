<?php

declare( strict_types = 1 );

namespace App\Form\User;

use Laminas\Form\Form;
use App\Model\Values\{IdentityFields as IFs, UserFields as UFs};

class InfoForm extends Form {
	public function __construct() {
		parent::__construct( 'user_info' );

		$this->add( [
			'name'       => UFs::ID,
			'type'       => 'number',
			'attributes' => [
				'id'          => UFs::ID,
				'class'       => 'input',
				'disabled'    => 'disabled',
				'placeholder' => 'User ID',
			],
			'options'    => [
				'label'            => 'User ID:',
				'label_attributes' => [
					'class' => 'label',
				],
			],
		] );
		$this->add( [
			'name'       => IFs::NAME,
			'type'       => 'text',
			'attributes' => [
				'id'          => IFs::NAME,
				'class'       => 'input',
				'disabled'    => 'disabled',
				'placeholder' => 'Username',
			],
			'options'    => [
				'label'            => 'Username:',
				'label_attributes' => [
					'class' => 'label',
				],
			],
		] );
		$this->add( [
			'name'       => IFs::UPDATED_ON,
			'type'       => 'text',
			'attributes' => [
				'id'          => IFs::UPDATED_ON,
				'class'       => 'input',
				'disabled'    => 'disabled',
				'placeholder' => 'Updated Date',
			],
			'options'    => [
				'label'            => 'Last Updated:',
				'label_attributes' => [
					'class' => 'label',
				],
			],
		] );
		$this->add( [
			'name'       => IFs::CREATED_ON,
			'type'       => 'text',
			'attributes' => [
				'id'          => IFs::CREATED_ON,
				'class'       => 'input',
				'disabled'    => 'disabled',
				'placeholder' => 'Creation Date',
			],
			'options'    => [
				'label'            => 'Time Created:',
				'label_attributes' => [
					'class' => 'label',
				],
			],
		] );
	}
}