<?php

declare( strict_types = 1 );

namespace App\Model;

use App\Model\Traits\{TraitComposition, TraitAdminComposite, TraitPageVisitComposite};

class AdminPageVisit implements ModelInterface {
	use TraitComposition, TraitAdminComposite, TraitPageVisitComposite;

	/**
	 * @param Admin     $Admin
	 * @param PageVisit $PageVisit
	 * @param ?int      $id
	 */
	public function __construct( Admin $Admin, PageVisit $PageVisit, ?int $id = null ) {
		$this->setId( $id );
		$this->setAdmin( $Admin );
		$this->setPageVisit( $PageVisit );
	}
}