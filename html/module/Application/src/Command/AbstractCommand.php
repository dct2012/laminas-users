<?php

namespace Application\Command;

use Application\Model\ModelInterface;
use Laminas\Hydrator\ReflectionHydrator;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Adapter\Driver\ResultInterface;
use Laminas\Db\Sql\{Delete, Insert, PreparableSqlInterface, Select, Sql, Update};
use Application\Exceptions\{DbOperationErrorException as ErrorException, DbOperationHadNoAffectException as NoAffectException};

abstract class AbstractCommand {
	/** @var AdapterInterface */
	protected AdapterInterface $db;

	/** @param AdapterInterface $db */
	public function __construct( AdapterInterface $db ) {
		$this->db = $db;
	}

	/** @return string */
	abstract static protected function getTableName(): string;

	/** @return array */
	abstract static protected function getTableColumns(): array;

	/**
	 * @param string                 $action
	 * @param PreparableSqlInterface $Statement
	 *
	 * @return ResultInterface
	 */
	protected function executeSqlStatement( string $action, PreparableSqlInterface $Statement ): ResultInterface {
		$Result = ( new Sql ( $this->db ) )->prepareStatementForSqlObject( $Statement )->execute();
		if( !$Result instanceof ResultInterface ) {
			throw new ErrorException( "Database error occurred during {$this::getTableName()} {$action} operation!" );
		}
		if( $Result->getAffectedRows() == 0 ) {
			throw new NoAffectException( "Failed to {$action} on {$this::getTableName()}!" );
		}

		return $Result;
	}

	/**
	 * @param ModelInterface $Model
	 * @param array          $values
	 *
	 * @return ModelInterface
	 */
	public function create( ModelInterface $Model, array $values = [] ): ModelInterface {
		$Result = $this->executeSqlStatement( 'insert', ( new Insert( $this::getTableName() ) )
			->values( $values ) );

		return $this->read( $Model->setId( $Result->getGeneratedValue() ) );
	}

	/**
	 * @param ModelInterface $Model
	 * @param array          $where
	 *
	 * @return ModelInterface
	 */
	public function read( ModelInterface $Model, array $where = [] ): ModelInterface {
		$Select = ( new Select( $this::getTableName() ) )
			->columns( $this::getTableColumns() )
			->where( $where );

		$Result = $this->executeSqlStatement( 'select', $Select );
		foreach( ( new HydratingResultSet( new ReflectionHydrator(), $Model ) )->initialize( $Result ) as $u ) {
			$Model = $u;
		}

		return $Model;
	}

	/**
	 * @param ModelInterface $Model
	 * @param array          $values
	 * @param array          $where
	 *
	 * @return ModelInterface
	 */
	public function update( ModelInterface $Model, array $values = [], array $where = [] ): ModelInterface {
		$this->executeSqlStatement( 'update', ( new Update( $this::getTableName() ) )
			->set( $values )
			->where( $where ) );

		return $Model;
	}

	/**
	 * @param ModelInterface $Model
	 * @param array          $where
	 *
	 * @return ModelInterface
	 */
	public function delete( ModelInterface $Model, array $where = [] ): ModelInterface {
		$this->executeSqlStatement( 'delete', ( new Delete( $this::getTableName() ) )
			->where( $where ) );

		return $Model;
	}
}