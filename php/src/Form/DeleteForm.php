<?php

declare( strict_types = 1 );

namespace App\Form;

use Laminas\Form\Element\Csrf;
use App\Model\Values\UserFields as UFs;

class DeleteForm extends AbstractForm {
	public function __construct() {
		parent::__construct( 'delete' );

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