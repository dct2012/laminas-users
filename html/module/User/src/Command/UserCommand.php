<?php

namespace User\Command;

use ReflectionException, RuntimeException;
use User\Enum\UserFields as UFs;
use Application\Model\ModelInterface;
use Application\Command\AbstractCommand;
use Application\Exceptions\DbOperationHadNoAffectException;

class UserCommand extends AbstractCommand {
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
		try {
			$User = $this->read( $User );
		} catch( DbOperationHadNoAffectException $e ) {
			// Successfull
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
	 *
	 * @return ModelInterface|array
	 */
	public function read( ModelInterface $User, array $where = [] ) {
		return parent::read( $User, empty( $where )
			? [ UFs::USERNAME => $User->getUserName() ]
			: $where );
	}

	/**
	 * @param ModelInterface $User
	 * @param array          $values
	 * @param array          $where
	 *
	 * @return ModelInterface
	 */
	public function update( ModelInterface $User, array $values = [], array $where = [] ): ModelInterface {
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
		return parent::delete( $User, empty( $where )
			? [ UFs::USERNAME => $User->getUserName() ]
			: $where );
	}
}