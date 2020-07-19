<?php

namespace User\Form;

use User\Model\User;
use Laminas\Form\Form;
use Laminas\Form\Element\Csrf;
use Laminas\Hydrator\ReflectionHydrator;

class DeleteForm extends Form {
	public function __construct() {
		parent::__construct( 'delete' );

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
				'name'    => 'password',
				'type'    => 'password',
				'options' => [
					'label' => 'Current Password:',
				],
			]
		);
		$this->add( new Csrf( 'security' ) );
		$this->add(
			[
				'name'       => 'delete',
				'type'       => 'submit',
				'attributes' => [
					'value' => 'Delete',
					'id'    => 'deleteButton',
				],
			]
		);
	}
}