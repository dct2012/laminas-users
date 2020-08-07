<?php

declare( strict_types = 1 );

namespace App\Model;

use App\Model\Traits\{TraitComposition, TraitUserComposite, TraitPageVisitComposite};

class UserPageVisit implements ModelInterface {
	use TraitComposition, TraitUserComposite, TraitPageVisitComposite;

	/**
	 * @param User      $User
	 * @param PageVisit $PageVisit
	 * @param ?int      $id
	 */
	public function __construct( User $User, PageVisit $PageVisit, ?int $id = null ) {
		$this->setId( $id );
		$this->setUser( $User );
		$this->setPageVisit( $PageVisit );
	}
}