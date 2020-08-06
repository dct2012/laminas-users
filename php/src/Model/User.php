<?php

declare( strict_types = 1 );

namespace App\Model;

use App\Model\Traits\{TraitComposition, TraitIdentityComposite};

class User implements ModelInterface {
	use TraitComposition, TraitIdentityComposite;

	/**
	 * @param Identity $Identity
	 * @param ?int     $id
	 */
	public function __construct( Identity $Identity, ?int $id = null ) {
		$this->setId( $id );
		$this->setIdentity( $Identity );
	}
}