<?php

namespace User\Form;

use User\Model\User;
use User\Enum\UserFields as UFs;
use Laminas\Form\Form;
use Laminas\Form\Element\Csrf;
use Laminas\Hydrator\ReflectionHydrator;

class DeleteForm extends Form {
	public function __construct() {
		parent::__construct( 'delete' );

		$this->setHydrator( new ReflectionHydrator() );
		$this->setObject( new User( '', '' ) );

		$this->add( [
			'name' => UFs::ID,
			'type' => 'hidden',
		] );
		$this->add( [
			'name' => UFs::USERNAME,
			'type' => 'hidden',
		] );
		$this->add( [
			'name'    => UFs::PASSWORD,
			'type'    => 'password',
			'options' => [
				'label' => 'Current Password:',
			],
		] );
		$this->add( new Csrf( 'security' ) );
		$this->add( [
			'name'       => 'delete',
			'type'       => 'submit',
			'attributes' => [
				'value' => 'Delete',
				'id'    => 'deleteButton',
			],
		] );
	}
}