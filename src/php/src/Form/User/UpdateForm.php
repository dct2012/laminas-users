<?php

declare( strict_types = 1 );

namespace App\Form\User;

use Laminas\Form\Form;
use Laminas\Form\Element\Csrf;
use App\Model\Values\IdentityFields as IFs;

class UpdateForm extends Form {
	const CURRENT_PASSWORD    = 'current-password';
	const VERIFY_NEW_PASSWORD = 'verify-new-password';

	public function __construct() {
		parent::__construct( 'user_update' );

		$this->add( [
			'name'       => self::CURRENT_PASSWORD,
			'type'       => 'password',
			'attributes' => [
				'id'           => self::CURRENT_PASSWORD,
				'class'        => 'input toggle-password',
				'required'     => 'required',
				'placeholder'  => 'Password',
				'autocomplete' => 'current-password',
			],
			'options'    => [
				'label'            => 'Current Password:',
				'label_attributes' => [
					'class' => 'label',
				],
			],
		] );
		$this->add( [
			'name'       => IFs::PASSWORD,
			'type'       => 'password',
			'attributes' => [
				'id'           => IFs::PASSWORD,
				'class'        => 'input toggle-password',
				'required'     => 'required',
				'placeholder'  => 'Password',
				'autocomplete' => 'new-password',
			],
			'options'    => [
				'label'            => 'New Password:',
				'label_attributes' => [
					'class' => 'label',
				],
			],
		] );
		$this->add( [
			'name'       => self::VERIFY_NEW_PASSWORD,
			'type'       => 'password',
			'attributes' => [
				'id'           => self::VERIFY_NEW_PASSWORD,
				'class'        => 'input toggle-password',
				'required'     => 'required',
				'placeholder'  => 'Password',
				'autocomplete' => 'new-password',
			],
			'options'    => [
				'label'            => 'Verify New Password:',
				'label_attributes' => [
					'class' => 'label',
				],
			],
		] );
		$this->add( new Csrf( 'security' ) );
		$this->add( [
			'name'       => 'update',
			'type'       => 'submit',
			'attributes' => [
				'value' => 'Update',
				'id'    => 'updateButton',
				'class' => 'button is-block is-info',

			],
		] );
	}
}