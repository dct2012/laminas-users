<?php

declare( strict_types = 1 );

namespace App\Model\Helper;

use App\Model\User;
use App\Model\Values\UserFields as UFs;
use Laminas\Db\Adapter\AdapterInterface;

class UserHelper extends AbstractHelper {
	/** @var IdentityHelper */
	protected IdentityHelper $IdentityHelper;

	/** @param AdapterInterface $db */
	public function __construct( AdapterInterface $db ) {
		parent::__construct( $db );
		$this->IdentityHelper = new IdentityHelper( $db );
	}

	/* @return string */
	static public function getTableName(): string {
		return 'users';
	}

	/** @return array */
	static protected function getTableColumns(): array {
		return array_values( UFs::getInstance()->getArrayCopy() );
	}

	/**
	 * @param User  $Model
	 * @param array $values
	 *
	 * @return User
	 */
	public function create( $Model, array $values = [] ) {
		/** @var User $Model */
		$Model = parent::create( $Model, empty( $values )
			? [ UFs::IDENTITY_ID => $Model->getIdentityId() ]
			: $values );

		return $Model;
	}

	/**
	 * @param User  $Model
	 * @param array $where
	 * @param array $order
	 *
	 * @return User|array
	 */
	public function read( $Model, array $where = [], array $order = [ UFs::ID => 'ASC' ] ) {
		$Model = $Model->setIdentity( $this->IdentityHelper->read( $Model->getIdentity() ) );

		return parent::read( $Model, empty( $where )
			? [ UFs::IDENTITY_ID => $Model->getIdentityId() ]
			: $where,
			$order );
	}

	/**
	 * @param User  $Model
	 * @param array $values
	 * @param array $where
	 *
	 * @return User
	 */
	public function update( $Model, array $values = [], array $where = [] ) {
		return $Model->setIdentity( $this->IdentityHelper->update( $Model->getIdentity(), $where ) );
	}

	/**
	 * @param User  $Model
	 * @param array $where
	 *
	 * @return User
	 */
	public function delete( $Model, array $where = [] ) {
		return $Model->setIdentity( $this->IdentityHelper->delete( $Model->getIdentity(), $where ) );
	}
}