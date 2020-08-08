<?php

declare( strict_types = 1 );

namespace App\Form\Admin;

use Laminas\Form\Form;
use App\Model\Values\{AdminFields as AFs, IdentityFields as IFs};

class InfoForm extends Form {
	public function __construct() {
		parent::__construct( 'admin_info' );

		$this->add( [
			'name'       => AFs::ID,
			'type'       => 'number',
			'attributes' => [
				'id'          => AFs::ID,
				'class'       => 'input',
				'disabled'    => 'disabled',
				'placeholder' => 'Admin ID',
			],
			'options'    => [
				'label'            => 'Admin ID:',
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
				'placeholder' => 'Admin Name',
			],
			'options'    => [
				'label'            => 'Admin Name:',
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