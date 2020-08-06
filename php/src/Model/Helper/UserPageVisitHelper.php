<?php

declare( strict_types = 1 );

namespace App\Model\Helper;

use App\Model\UserPageVisit;
use App\Model\Values\UserPageVisitFields as UPVFs;

class UserPageVisitHelper extends AbstractHelper {
	/** @return string */
	static protected function getTableName(): string {
		return 'user_page_visits';
	}

	/** @return array */
	static protected function getTableColumns(): array {
		return array_values( UPVFs::getInstance()->getArrayCopy() );
	}

	/**
	 * @param UserPageVisit $UserPageVisit
	 * @param array         $values
	 *
	 * @return UserPageVisit
	 */
	public function create( $UserPageVisit, array $values = [] ) {
		/** @var UserPageVisit $UserPageVisit */
		$UserPageVisit = parent::create( $UserPageVisit, empty( $values )
			? [ UPVFs::USER_ID => $UserPageVisit->getUserId(), UPVFs::PAGE_VISIT_ID => $UserPageVisit->getPageVisitId() ]
			: $values );

		return $UserPageVisit;
	}

	/**
	 * @param UserPageVisit $UserPageVisit
	 * @param array         $where
	 * @param array         $order
	 *
	 * @return UserPageVisit|array
	 */
	public function read( $UserPageVisit, array $where = [], array $order = [ UPVFs::ID => 'DESC' ] ) {
		return parent::read( $UserPageVisit, empty( $where )
			? [ UPVFs::ID => $UserPageVisit->getId() ]
			: $where,
			$order );
	}

	/**
	 * No reason to update a page visit at the moment, they're always a individual occurrence
	 *
	 * @param UserPageVisit $UserPageVisit
	 * @param array         $values
	 * @param array         $where
	 *
	 * @return UserPageVisit
	 */
	public function update( $UserPageVisit, array $values = [], array $where = [] ) {
		return $UserPageVisit;
	}

	/**
	 * @param UserPageVisit $UserPageVisit
	 * @param array         $where
	 *
	 * @return UserPageVisit
	 */
	public function delete( $UserPageVisit, array $where = [] ) {
		/** @var UserPageVisit $UserPageVisit */
		$UserPageVisit = parent::delete( $UserPageVisit, empty( $where )
			? [ UPVFs::ID => $UserPageVisit->getId() ]
			: $where );

		return $UserPageVisit;
	}
}