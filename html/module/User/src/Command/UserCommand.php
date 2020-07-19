<?php

namespace User\Command;

use User\Model\User;
use RuntimeException;
use Laminas\Hydrator\ReflectionHydrator;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use Laminas\Db\Adapter\Driver\ResultInterface;
use Laminas\Db\Sql\{Delete, Select, Update, Insert, Sql};

class UserCommand {
	/** @var AdapterInterface */
	private AdapterInterface $db;

	/** @param AdapterInterface $db */
	public function __construct( AdapterInterface $db ) {
		$this->db = $db;
	}

	/**
	 * @param User $User
	 * @return User
	 */
	public function create( User $User ): User {
		$username = $User->getUserName();
		$password = password_hash( $User->getPassword(), PASSWORD_DEFAULT );

		$User = $this->read( $User );
		if( !empty( $User->getId() ) ) {
			throw new RuntimeException( "Username: {$username}, already exists!" );
		}

		$Insert = ( new Insert( 'users' ) )
			->values( [ 'username' => $username, 'password' => $password ] );

		$Result = ( new Sql( $this->db ) )
			->prepareStatementForSqlObject( $Insert )
			->execute();
		if( !$Result instanceof ResultInterface ) {
			throw new RuntimeException( 'Database error occurred during user insert operation!' );
		}

		return new User( $username, $password, $Result->getGeneratedValue() );
	}

	/**
	 * @param User $User
	 * @return User
	 */
	public function read( User $User ): User {
		$Select = ( new Select( 'users' ) )
			->columns( [ 'id', 'username', 'password' ] )
			->where( [ 'username' => $User->getUserName() ] );

		$Result = ( new Sql( $this->db ) )
			->prepareStatementForSqlObject( $Select )
			->execute();
		if( !$Result instanceof ResultInterface ) {
			throw new RuntimeException( 'Database error occurred during user read operation!' );
		}

		foreach( ( new HydratingResultSet( new ReflectionHydrator(), $User ) )->initialize( $Result ) as $u ) {
			$User = $u;
		}

		return $User;
	}

	/**
	 * @param User $User
	 * @return User
	 */
	public function update( User $User ): User {
		$Update = ( new Update( 'users' ) )
			->set( [ 'password' => $User->setPassword( User::hashPassword( $User->getPassword() ) )->getPassword() ] )
			->where( [ 'username' => $User->getUserName() ] );

		/* @var Result $Result */
		$Result = ( new Sql( $this->db ) )
			->prepareStatementForSqlObject( $Update )
			->execute();

		if( !$Result instanceof ResultInterface ) {
			throw new RuntimeException( 'Database error occurred during updating user\'s password operation!' );
		}
		if( $Result->getAffectedRows() == 0 ) {
			throw new RuntimeException( 'Failed to update user\'s password!' );
		}

		return $User;
	}

	/**
	 * @param User $User
	 * @return User
	 */
	public function delete( User $User ): User {
		$Delete = ( new Delete( 'users' ) )
			->where( [ 'username' => $User->getUserName() ] );

		$Result = ( new Sql( $this->db ) )
			->prepareStatementForSqlObject( $Delete )
			->execute();
		if( !$Result instanceof ResultInterface ) {
			throw new RuntimeException( 'Database error occurred during user delete operation!' );
		}
		if( $Result->getAffectedRows() == 0 ) {
			throw new RuntimeException( 'Failed to delete user!' );
		}

		return $User;
	}
}