<?php

declare( strict_types = 1 );

namespace App\Model\Helper;

use Laminas\Db\Adapter\AdapterInterface;
use App\Model\AdminLogin;
use App\Model\Values\AdminLoginFields as ALFs;

class AdminLoginHelper extends AbstractHelper {
	/** @var LoginHelper */
	protected LoginHelper $LoginHelper;

	/** @param AdapterInterface $db */
	public function __construct( AdapterInterface $db ) {
		parent::__construct( $db );
		$this->LoginHelper = new LoginHelper( $db );
	}

	/** @return string */
	static protected function getTableName(): string {
		return 'admin_logins';
	}

	/** @return array */
	static protected function getTableColumns(): array {
		return array_values( ALFs::getInstance()->getArrayCopy() );
	}

	/**
	 * @param AdminLogin $AdminLogin
	 * @param array      $values
	 *
	 * @return AdminLogin
	 */
	public function create( $AdminLogin, array $values = [] ) {
		/** @var AdminLogin $AdminLogin */
		$AdminLogin = parent::create( $AdminLogin, empty( $values )
			? [ ALFs::ADMIN_ID => $AdminLogin->getAdminId(), ALFs::LOGIN_ID => $AdminLogin->getLoginId() ]
			: $values );

		return $AdminLogin;
	}

	/**
	 * @param AdminLogin $AdminLogin
	 * @param array      $where
	 * @param array      $order
	 *
	 * @return AdminLogin|array
	 */
	public function read( $AdminLogin, array $where = [], array $order = [ ALFs::ID => 'DESC' ] ) {
		return parent::read( $AdminLogin, empty( $where )
			? [ ALFs::ID => $AdminLogin->getId() ]
			: $where,
			$order );
	}

	/**
	 * The only use case for this is to update login.logout_time
	 *
	 * @param AdminLogin $AdminLogin
	 * @param array      $values
	 * @param array      $where
	 *
	 * @return AdminLogin
	 */
	public function update( $AdminLogin, array $values = [], array $where = [] ) {
		return $AdminLogin->setLogin( $this->LoginHelper->update( $AdminLogin->getLogin() ) );
	}

	/**.
	 * @param AdminLogin $AdminLogin
	 * @param array      $where
	 *
	 * @return AdminLogin
	 */
	public function delete( $AdminLogin, array $where = [] ) {
		return $AdminLogin;
	}
}