<?php

declare( strict_types = 1 );

namespace App\Model\Helper;

use App\Model\Identity;
use App\Model\Values\IdentityFields as IFs;

class IdentityHelper extends AbstractHelper {
	/* @return string */
	static public function getTableName(): string {
		return 'identities';
	}

	/** @return array */
	static protected function getTableColumns(): array {
		return array_values( IFs::getInstance()->getArrayCopy() );
	}

	/**
	 * @param Identity $Identity
	 * @param array    $values
	 *
	 * @return Identity
	 */
	public function create( $Identity, array $values = [] ) {
		/** @var Identity $Identity */
		$Identity = parent::create( $Identity, empty( $values )
			? [ IFs::NAME => $Identity->getName(), IFs::PASSWORD => $Identity->getPassword() ]
			: $values );

		return $Identity;
	}

	/**
	 * @param Identity $Identity
	 * @param array    $where
	 * @param array    $order
	 *
	 * @return Identity|array
	 */
	public function read( $Identity, array $where = [], array $order = [ IFs::ID => 'ASC' ] ) {
		return parent::read( $Identity, empty( $where )
			? [ IFs::NAME => $Identity->getName() ]
			: $where,
			$order );
	}

	/**
	 * @param Identity $Identity
	 * @param array    $values
	 * @param array    $where
	 *
	 * @return Identity
	 */
	public function update( $Identity, array $values = [], array $where = [] ) {
		/** @var Identity $Identity */
		$Identity = parent::update( $Identity,
			empty( $values )
				? [ IFs::PASSWORD => $Identity->getPassword() ]
				: $values,
			empty( $where )
				? [ IFs::NAME => $Identity->getName() ]
				: $where );

		return $Identity;
	}

	/**
	 * @param Identity $Identity
	 * @param array    $where
	 *
	 * @return Identity
	 */
	public function delete( $Identity, array $where = [] ) {
		/** @var Identity $Identity */
		$Identity = parent::delete( $Identity, empty( $where )
			? [ IFs::NAME => $Identity->getName() ]
			: $where );

		return $Identity;
	}
}