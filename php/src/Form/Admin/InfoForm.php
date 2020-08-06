<?php

declare( strict_types = 1 );

namespace App\Form\Admin;

use Laminas\Form\Form;
use App\Model\Values\{AdminFields as AFs, IdentityFields as IFs};

class InfoForm extends Form {
	public function __construct() {
		parent::__construct( 'admin_info' );

		$this->add( [
			'name'    => AFs::ID,
			'type'    => 'number',
			'options' => [
				'label' => 'Admin ID:',
			],
		] );
		$this->add( [
			'name'    => IFs::NAME,
			'type'    => 'text',
			'options' => [
				'label' => 'ame:',
			],
		] );
		$this->add( [
			'name'    => IFs::UPDATED_ON,
			'type'    => 'text',
			'options' => [
				'label' => 'Last Updated:',
			],
		] );
		$this->add( [
			'name'    => IFs::CREATED_ON,
			'type'    => 'text',
			'options' => [
				'label' => 'Created On:',
			],
		] );
	}
}