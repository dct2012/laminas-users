<?php

declare( strict_types = 1 );

namespace App\Form\User;

use Laminas\Form\Form;
use Laminas\Form\Element\Csrf;
use App\Model\Values\IdentityFields as IFs;

class DeleteForm extends Form {
	public function __construct() {
		parent::__construct( 'user_delete' );

		$this->add( [
			'name'       => IFs::PASSWORD,
			'type'       => 'password',
			'attributes' => [
				'id'           => IFs::PASSWORD,
				'class'        => 'input toggle-password',
				'required'     => 'required',
				'placeholder'  => 'Current Password',
				'autocomplete' => 'current-password',
			],
			'options'    => [
				'label'            => 'Current Password:',
				'label_attributes' => [
					'class' => 'label',
				],
			],
		] );
		$this->add( new Csrf( 'security' ) );
		$this->add( [
			'name'       => 'delete',
			'type'       => 'submit',
			'attributes' => [
				'value' => 'Delete',
				'id'    => 'deleteButton',
				'class' => 'button is-block is-danger',
			],
		] );
	}
}