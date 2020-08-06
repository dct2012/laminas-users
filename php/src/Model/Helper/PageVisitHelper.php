<?php

declare( strict_types = 1 );

namespace App\Model\Helper;

use App\Model\PageVisit;
use App\Model\Values\PageVisitFields as PVFs;

class PageVisitHelper extends AbstractHelper {
	/* @return string */
	static public function getTableName(): string {
		return 'page_visits';
	}

	/** @return array */
	static protected function getTableColumns(): array {
		return array_values( PVFs::getInstance()->getArrayCopy() );
	}

	/**
	 * @param PageVisit $PageVisit
	 * @param array     $values
	 *
	 * @return PageVisit
	 */
	public function create( $PageVisit, array $values = [] ) {
		/** @var PageVisit $PageVisit */
		$PageVisit = parent::create( $PageVisit, empty( $values )
			? [ PVFs::PAGE => $PageVisit->getPage(), PVFs::IP_ADDRESS => $PageVisit->getIpAddress(), PVFs::USER_AGENT => $PageVisit->getUserAgent(), ]
			: $values );

		return $PageVisit;
	}

	/**
	 * @param PageVisit $PageVisit
	 * @param array     $where
	 * @param array     $order
	 *
	 * @return PageVisit|array
	 */
	public function read( $PageVisit, array $where = [], array $order = [ PVFs::ID => 'DESC' ] ) {
		return parent::read( $PageVisit, empty( $where )
			? [ PVFs::ID => $PageVisit->getId() ]
			: $where,
			$order );
	}

	/**
	 * At the moment there is never a case for updating
	 *
	 * @param PageVisit $PageVisit
	 * @param array     $values
	 * @param array     $where
	 *
	 * @return PageVisit
	 */
	public function update( $PageVisit, array $values = [], array $where = [] ) {
		return $PageVisit;
	}

	/**
	 * @param PageVisit $PageVisit
	 * @param array     $where
	 *
	 * @return PageVisit
	 */
	public function delete( $PageVisit, array $where = [] ) {
		return $PageVisit;
	}
}