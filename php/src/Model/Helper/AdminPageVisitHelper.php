<?php

declare( strict_types = 1 );

namespace App\Model\Helper;

use App\Model\AdminPageVisit;
use App\Model\Values\AdminPageVisitFields as APVFs;

class AdminPageVisitHelper extends AbstractHelper {
	/** @return string */
	static protected function getTableName(): string {
		return 'admin_page_visits';
	}

	/** @return array */
	static protected function getTableColumns(): array {
		return array_values( APVFs::getInstance()->getArrayCopy() );
	}

	/**
	 * @param AdminPageVisit $AdminPageVisit
	 * @param array          $values
	 *
	 * @return AdminPageVisit
	 */
	public function create( $AdminPageVisit, array $values = [] ) {
		/** @var AdminPageVisit $AdminPageVisit */
		$AdminPageVisit = parent::create( $AdminPageVisit, empty( $values )
			? [ APVFs::ADMIN_ID => $AdminPageVisit->getAdminId(), APVFs::PAGE_VISIT_ID => $AdminPageVisit->getPageVisitId() ]
			: $values );

		return $AdminPageVisit;
	}

	/**
	 * @param AdminPageVisit $AdminPageVisit
	 * @param array          $where
	 * @param array          $order
	 *
	 * @return AdminPageVisit|array
	 */
	public function read( $AdminPageVisit, array $where = [], array $order = [ APVFs::ID => 'DESC' ] ) {
		return parent::read( $AdminPageVisit, empty( $where )
			? [ APVFs::ID => $AdminPageVisit->getId() ]
			: $where,
			$order );
	}

	/**
	 * No reason to update a page visit at the moment, they're always a individual occurrence
	 *
	 * @param AdminPageVisit $AdminPageVisit
	 * @param array          $values
	 * @param array          $where
	 *
	 * @return AdminPageVisit
	 */
	public function update( $AdminPageVisit, array $values = [], array $where = [] ) {
		return $AdminPageVisit;
	}

	/**
	 * @param AdminPageVisit $AdminPageVisit
	 * @param array          $where
	 *
	 * @return AdminPageVisit
	 */
	public function delete( $AdminPageVisit, array $where = [] ) {
		/** @var AdminPageVisit $AdminPageVisit */
		$AdminPageVisit = parent::delete( $AdminPageVisit, empty( $where )
			? [ APVFs::ID => $AdminPageVisit->getId() ]
			: $where );

		return $AdminPageVisit;
	}
}