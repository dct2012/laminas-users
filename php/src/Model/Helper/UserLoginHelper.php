<?php

declare( strict_types = 1 );

namespace App\Model\Helper;

use Laminas\Db\Adapter\AdapterInterface;
use App\Model\UserLogin;
use App\Model\Values\UserLoginFields as ULFs;

class UserLoginHelper extends AbstractHelper {
	/** @var LoginHelper */
	protected LoginHelper $LoginHelper;

	/** @param AdapterInterface $db */
	public function __construct( AdapterInterface $db ) {
		parent::__construct( $db );
		$this->LoginHelper = new LoginHelper( $db );
	}

	/** @return string */
	static protected function getTableName(): string {
		return 'user_logins';
	}

	/** @return array */
	static protected function getTableColumns(): array {
		return array_values( ULFs::getInstance()->getArrayCopy() );
	}

	/**
	 * @param UserLogin $UserLogin
	 * @param array     $values
	 *
	 * @return UserLogin
	 */
	public function create( $UserLogin, array $values = [] ) {
		/** @var UserLogin $UserLogin */
		$UserLogin = parent::create( $UserLogin, empty( $values )
			? [ ULFs::USER_ID => $UserLogin->getUserId(), ULFs::LOGIN_ID => $UserLogin->getLoginId() ]
			: $values );

		return $UserLogin;
	}

	/**
	 * @param UserLogin $UserLogin
	 * @param array     $where
	 * @param array     $order
	 *
	 * @return UserLogin|array
	 */
	public function read( $UserLogin, array $where = [], array $order = [ ULFs::ID => 'DESC' ] ) {
		return parent::read( $UserLogin, empty( $where )
			? [ ULFs::ID => $UserLogin->getId() ]
			: $where,
			$order );
	}

	/**
	 * The only use case for this is to update login.logout_time
	 *
	 * @param UserLogin $UserLogin
	 * @param array     $values
	 * @param array     $where
	 *
	 * @return UserLogin
	 */
	public function update( $UserLogin, array $values = [], array $where = [] ) {
		return $UserLogin->setLogin( $this->LoginHelper->update( $UserLogin->getLogin() ) );
	}

	/**
	 * @param UserLogin $UserLogin
	 * @param array     $where
	 *
	 * @return UserLogin
	 */
	public function delete( $UserLogin, array $where = [] ) {
		return $UserLogin;
	}
}