<?php

declare( strict_types = 1 );

namespace App\Model\Helper;

use App\Model\Admin;
use App\Model\Values\AdminFields as AFs;
use Laminas\Db\Adapter\AdapterInterface;

class AdminHelper extends AbstractHelper {
	/** @var IdentityHelper */
	protected IdentityHelper $IdentityHelper;

	/** @param AdapterInterface $db */
	public function __construct( AdapterInterface $db ) {
		parent::__construct( $db );
		$this->IdentityHelper = new IdentityHelper( $db );
	}

	/* @return string */
	static public function getTableName(): string {
		return 'admins';
	}

	/** @return array */
	static protected function getTableColumns(): array {
		return array_values( AFs::getInstance()->getArrayCopy() );
	}

	/**
	 * @param Admin $Admin
	 * @param array $values
	 *
	 * @return Admin
	 */
	public function create( $Admin, array $values = [] ) {
		/** @var Admin $Admin */
		$Admin = parent::create( $Admin, empty( $values )
			? [ AFs::IDENTITY_ID => $Admin->getIdentityId() ]
			: $values );

		return $Admin;
	}

	/**
	 * @param Admin $Admin
	 * @param array $where
	 * @param array $order
	 *
	 * @return Admin|array
	 */
	public function read( $Admin, array $where = [], array $order = [ AFs::ID => 'ASC' ] ) {
		$Admin = $Admin->setIdentity( $this->IdentityHelper->read( $Admin->getIdentity() ) );

		return parent::read( $Admin, empty( $where )
			? [ AFs::IDENTITY_ID => $Admin->getIdentityId() ]
			: $where,
			$order );
	}

	/**
	 * @param Admin $Admin
	 * @param array $values
	 * @param array $where
	 *
	 * @return Admin
	 */
	public function update( $Admin, array $values = [], array $where = [] ) {
		return $Admin->setIdentity( $this->IdentityHelper->update( $Admin->getIdentity(), $where ) );
	}

	/**
	 * @param Admin $Admin
	 * @param array $where
	 *
	 * @return Admin
	 */
	public function delete( $Admin, array $where = [] ) {
		return $Admin->setIdentity( $this->IdentityHelper->delete( $Admin->getIdentity(), $where ) );
	}
}