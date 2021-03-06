<?php

declare( strict_types = 1 );

namespace App\Model;

use App\Model\Traits\{TraitComposition, TraitIdentityComposite, TraitLoginComposite};

class Admin implements ModelInterface {
	use TraitComposition, TraitIdentityComposite, TraitLoginComposite;

	/**
	 * @param Identity $Identity
	 * @param ?int     $id
	 */
	public function __construct( Identity $Identity, ?int $id = null ) {
		$this->setId( $id );
		$this->setIdentity( $Identity );
	}
}