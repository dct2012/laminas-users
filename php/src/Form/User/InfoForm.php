<?php

declare( strict_types = 1 );

namespace App\Form\User;

use Laminas\Form\Form;
use App\Model\Values\{IdentityFields as IFs, UserFields as UFs};

class InfoForm extends Form {
	public function __construct() {
		parent::__construct( 'user_info' );

		$this->add( [
			'name'    => UFs::ID,
			'type'    => 'number',
			'options' => [
				'label' => 'ID:',
			],
		] );
		$this->add( [
			'name'    => IFs::NAME,
			'type'    => 'text',
			'options' => [
				'label' => 'Username:',
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