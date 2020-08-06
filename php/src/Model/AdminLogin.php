<?php

declare( strict_types = 1 );

namespace App\Model;

use App\Model\Traits\{TraitComposition, TraitAdminComposite, TraitLoginComposite};

class AdminLogin implements ModelInterface {
	use TraitComposition, TraitAdminComposite, TraitLoginComposite;

	/**
	 * @param Admin $Admin
	 * @param Login $Login
	 * @param ?int  $id
	 */
	public function __construct( Admin $Admin, Login $Login, ?int $id = null ) {
		$this->setId( $id );
		$this->setAdmin( $Admin );
		$this->setLogin( $Login );
	}
}