<?php

namespace User\Form;

use User\Model\User;
use Laminas\Form\Form;
use Laminas\Form\Element\Csrf;
use Laminas\Hydrator\ReflectionHydrator;

class UpdateForm extends Form {
	public function __construct() {
		parent::__construct( 'update' );

		$this->setHydrator( new ReflectionHydrator() );
		$this->setObject( new User( '', '' ) );

		$this->add(
			[
				'name' => 'id',
				'type' => 'hidden',
			]
		);
		$this->add(
			[
				'name' => 'username',
				'type' => 'hidden',
			]
		);
		$this->add(
			[
				'name'    => 'current_password',
				'type'    => 'password',
				'options' => [
					'label' => 'Current Password:',
				],
			]
		);
		$this->add(
			[
				'name'    => 'password',
				'type'    => 'password',
				'options' => [
					'label' => 'New Password:',
				],
			]
		);
		$this->add(
			[
				'name'    => 'verify_new_password',
				'type'    => 'password',
				'options' => [
					'label' => 'Verify New Password:',
				],
			]
		);
		$this->add( new Csrf( 'security' ) );
		$this->add(
			[
				'name'       => 'update',
				'type'       => 'submit',
				'attributes' => [
					'value' => 'Update',
					'id'    => 'updateButton',
				],
			]
		);
	}
}