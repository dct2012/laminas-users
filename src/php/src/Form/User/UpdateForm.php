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
			'name'    => self::CURRENT_PASSWORD,
			'type'    => 'password',
			'options' => [
				'label' => 'Current Password:',
			],
		] );
		$this->add( [
			'name'    => IFs::PASSWORD,
			'type'    => 'password',
			'options' => [
				'label' => 'New Password:',
			],
		] );
		$this->add( [
			'name'    => self::VERIFY_NEW_PASSWORD,
			'type'    => 'password',
			'options' => [
				'label' => 'Verify New Password:',
			],
		] );
		$this->add( new Csrf( 'security' ) );
		$this->add( [
			'name'       => 'update',
			'type'       => 'submit',
			'attributes' => [
				'value' => 'Update',
				'id'    => 'updateButton',
			],
		] );
	}
}