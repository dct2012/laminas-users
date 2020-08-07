<?php

declare( strict_types = 1 );

namespace App\Model\Helper;

use App\Functions;
use App\Model\Login;
use App\Model\Values\LoginFields as LFs;

class LoginHelper extends AbstractHelper {
	/* @return string */
	static public function getTableName(): string {
		return 'logins';
	}

	/** @return array */
	static protected function getTableColumns(): array {
		return array_values( LFs::getInstance()->getArrayCopy() );
	}

	/**
	 * @param Login $Login
	 * @param array $values
	 *
	 * @return Login
	 */
	public function create( $Login, array $values = [] ) {
		/** @var Login $Login */
		$Login = parent::create( $Login, empty( $values )
			? [ LFs::IP_ADDRESS => $Login->getIpAddress(), LFs::USER_AGENT => $Login->getUserAgent() ]
			: $values );

		return $Login;
	}

	/**
	 * @param Login $Login
	 * @param array $where
	 * @param array $order
	 *
	 * @return Login|array
	 */
	public function read( $Login, array $where = [], array $order = [ 'id' => 'ASC' ] ) {
		return parent::read( $Login, empty( $where )
			? [ LFs::ID => $Login->getId() ]
			: $where,
			$order );
	}

	/**
	 * @param Login $Login
	 * @param array $values
	 * @param array $where
	 *
	 * @return Login
	 */
	public function update( $Login, array $values = [], array $where = [] ) {
		/** @var Login $Login */
		$Login = parent::update( $Login,
			empty( $values )
				? [ LFs::LOGOUT_TIME => Functions::createTimeStamp() ]
				: $values,
			empty( $where )
				? [ LFs::ID => $Login->getId() ]
				: $where );

		return $Login;
	}

	/**
	 * @param Login $Login
	 * @param array $where
	 *
	 * @return Login
	 */
	public function delete( $Login, array $where = [] ) {
		/** @var Login $Login */
		$Login = parent::delete( $Login, empty( $where )
			? [ LFs::ID => $Login->getId() ]
			: $where );

		return $Login;
	}
}