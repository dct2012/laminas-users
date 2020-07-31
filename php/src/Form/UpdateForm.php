<?php

declare( strict_types = 1 );

namespace App\Form;

use Laminas\Form\Element\Csrf;
use App\Model\Values\UserFields as UFs;

class UpdateForm extends AbstractForm {
	public function __construct() {
		parent::__construct( 'update' );

		$this->add( [
			'name' => UFs::ID,
			'type' => 'hidden',
		] );
		$this->add( [
			'name' => UFs::USERNAME,
			'type' => 'hidden',
		] );
		$this->add( [
			'name'    => 'current_password',
			'type'    => 'password',
			'options' => [
				'label' => 'Current Password:',
			],
		] );
		$this->add( [
			'name'    => UFs::PASSWORD,
			'type'    => 'password',
			'options' => [
				'label' => 'New Password:',
			],
		] );
		$this->add( [
			'name'    => 'verify_new_password',
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