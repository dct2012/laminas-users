<?php

declare( strict_types = 1 );

namespace App\Model\Helper;

use App\Model\ModelInterface;
use App\Exception\{DbOperationErrorException as ErrorException, DbOperationHadNoAffectException as NoAffectException};
use Laminas\Hydrator\ReflectionHydrator;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Adapter\Driver\ResultInterface;
use Laminas\Db\Sql\{Delete, Insert, PreparableSqlInterface, Select, Sql, Update};

abstract class AbstractModelHelper {
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

		return $this->read( $Model->setId( (int)$Result->getGeneratedValue() ) );
	}

	/**
	 * @param ModelInterface $Model
	 * @param array          $where
	 * @param array          $order
	 *
	 * @return ModelInterface|array
	 */
	public function read( ModelInterface $Model, array $where = [], array $order = [ 'id' => 'ASC' ] ) {
		$Result = $this->executeSqlStatement( 'select', ( new Select( $this::getTableName() ) )
			->columns( $this::getTableColumns() )
			->where( $where )
			->order( $order ) );

		$models = [];
		foreach( ( new HydratingResultSet( new ReflectionHydrator(), $Model ) )->initialize( $Result ) as $M ) {
			$models[] = $M;
		}

		return count( $models ) == 1
			? $models[ 0 ]
			: $models;
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