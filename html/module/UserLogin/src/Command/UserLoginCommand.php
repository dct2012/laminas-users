<?php

namespace UserLogin\Command;

use ReflectionException;
use UserLogin\Model\UserLogin;
use Application\Model\ModelInterface;
use Application\Command\AbstractCommand;
use UserLogin\Enum\UserLoginFields as ULFs;

class UserLoginCommand extends AbstractCommand {
	/** @return string */
	static protected function getTableName(): string {
		return 'user_logins';
	}

	/**
	 * @return array
	 * @throws ReflectionException
	 */
	static protected function getTableColumns(): array {
		return array_values( ULFs::getInstance()->getArrayCopy() );
	}

	/**
	 * @param ModelInterface $UserLogin
	 * @param array          $values
	 *
	 * @return ModelInterface
	 */
	public function create( ModelInterface $UserLogin, array $values = [] ): ModelInterface {
		/** @var UserLogin $UserLogin */
		return parent::create( $UserLogin, empty( $values )
			? [ ULFs::USER_ID => $UserLogin->getUserId(), ULFs::IP_ADDRESS => $UserLogin->getIpAddress(), ULFs::DEVICE => $UserLogin->getDevice() ]
			: $values );
	}

	/**
	 * @param ModelInterface $UserLogin
	 * @param array          $where
	 * @param array          $order
	 *
	 * @return ModelInterface|array
	 */
	public function read( ModelInterface $UserLogin, array $where = [], array $order = [ ULFs::ID => 'DESC' ] ) {
		/** @var UserLogin $UserLogin */
		return parent::read(
			$UserLogin,
			empty( $where ) ? [ ULFs::ID => $UserLogin->getId() ] : $where,
			$order
		);
	}

	/**
	 * At the moment there is no reason to ever update a user_login entry
	 *
	 * @param ModelInterface $UserLogin
	 * @param array          $values
	 * @param array          $where
	 *
	 * @return ModelInterface
	 */
	public function update( ModelInterface $UserLogin, array $values = [], array $where = [] ): ModelInterface {
		/** @var UserLogin $UserLogin */
		return parent::update( $UserLogin,
			empty( $values )
				? [ ULFs::LOGOUT_TIME => date( 'Y-m-d h:i:s' ) ]
				: $values,
			empty( $where )
				? [ ULFs::ID => $UserLogin->getId() ]
				: $where );
	}

	/**
	 * @param ModelInterface $UserLogin
	 * @param array          $where
	 *
	 * @return ModelInterface
	 */
	public function delete( ModelInterface $UserLogin, array $where = [] ): ModelInterface {
		/** @var UserLogin $UserLogin */
		return parent::delete( $UserLogin, empty( $where )
			? [ ULFs::ID => $UserLogin->getId() ]
			: $where );
	}
}