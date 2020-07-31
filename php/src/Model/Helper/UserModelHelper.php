<?php

declare( strict_types = 1 );

namespace App\Model\Helper;

use ReflectionException, RuntimeException;
use App\Model\{ModelInterface, User};
use App\Model\Values\UserFields as UFs;
use App\Exception\DbOperationHadNoAffectException;

class UserModelHelper extends AbstractModelHelper {
	/* @return string */
	static public function getTableName(): string {
		return 'users';
	}

	/**
	 * @return array
	 * @throws ReflectionException
	 */
	static protected function getTableColumns(): array {
		return array_values( UFs::getInstance()->getArrayCopy() );
	}

	/**
	 * @param ModelInterface $User
	 * @param array          $values
	 *
	 * @return ModelInterface
	 */
	public function create( ModelInterface $User, array $values = [] ): ModelInterface {
		/** @var User $User */
		try {
			$User = $this->read( $User );
		} catch( DbOperationHadNoAffectException $e ) {
			// Successful
		}

		if( !empty( $User->getId() ) ) {
			throw new RuntimeException( "Username: {$User->getUserName()}, already exists!" );
		}

		return parent::create( $User, empty( $values )
			? [ UFs::USERNAME => $User->getUserName(), UFs::PASSWORD => $User->getPassword() ]
			: $values );
	}

	/**
	 * @param ModelInterface $User
	 * @param array          $where
	 * @param array          $order
	 *
	 * @return ModelInterface|array
	 */
	public function read( ModelInterface $User, array $where = [], array $order = [ UFs::ID => 'ASC' ] ) {
		/** @var User $User */
		return parent::read(
			$User,
			empty( $where ) ? [ UFs::USERNAME => $User->getUserName() ] : $where,
			$order );
	}

	/**
	 * @param ModelInterface $User
	 * @param array          $values
	 * @param array          $where
	 *
	 * @return ModelInterface
	 */
	public function update( ModelInterface $User, array $values = [], array $where = [] ): ModelInterface {
		/** @var User $User */
		return parent::update( $User,
			empty( $values )
				? [ UFs::PASSWORD => $User->getPassword() ]
				: $values,
			empty( $where )
				? [ UFs::USERNAME => $User->getUserName() ]
				: $where );
	}

	/**
	 * @param ModelInterface $User
	 * @param array          $where
	 *
	 * @return ModelInterface
	 */
	public function delete( ModelInterface $User, array $where = [] ): ModelInterface {
		/** @var User $User */
		return parent::delete( $User, empty( $where )
			? [ UFs::USERNAME => $User->getUserName() ]
			: $where );
	}
}