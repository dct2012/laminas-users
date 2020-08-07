<?php

declare( strict_types = 1 );

namespace App\Model;

use App\Model\Traits\{TraitComposition, TraitUserComposite, TraitLoginComposite};

class UserLogin implements ModelInterface {
	use TraitComposition, TraitUserComposite, TraitLoginComposite;

	/**
	 * @param User  $User
	 * @param Login $Login
	 * @param ?int  $id
	 */
	public function __construct( User $User, Login $Login, ?int $id = null ) {
		$this->setId( $id );
		$this->setUser( $User );
		$this->setLogin( $Login );
	}
}