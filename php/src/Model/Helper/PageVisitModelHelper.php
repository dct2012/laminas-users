<?php

declare( strict_types = 1 );

namespace App\Model\Helper;

use ReflectionException;
use App\Model\Values\PageVisitFields as PVFs;
use App\Model\{ModelInterface, PageVisit};

class PageVisitModelHelper extends AbstractModelHelper {
	/* @return string */
	static public function getTableName(): string {
		return 'page_visits';
	}

	/**
	 * @return array
	 * @throws ReflectionException
	 */
	static protected function getTableColumns(): array {
		return array_values( PVFs::getInstance()->getArrayCopy() );
	}

	/**
	 * @param ModelInterface $PageVisit
	 * @param array          $values
	 *
	 * @return ModelInterface
	 */
	public function create( ModelInterface $PageVisit, array $values = [] ): ModelInterface {
		/** @var PageVisit $PageVisit */
		return parent::create( $PageVisit, empty( $values )
			? [
				PVFs::PAGE       => $PageVisit->getPage(),
				PVFs::USER_ID    => $PageVisit->getUserId(),
				PVFs::IP_ADDRESS => $PageVisit->getIpAddress(),
				PVFs::DEVICE     => $PageVisit->getDevice(),
			]
			: $values );
	}

	/**
	 * @param ModelInterface $PageVisit
	 * @param array          $where
	 * @param array          $order
	 *
	 * @return ModelInterface|array
	 */
	public function read( ModelInterface $PageVisit, array $where = [], array $order = [ PVFs::ID => 'DESC' ] ) {
		/** @var PageVisit $PageVisit */
		return parent::read(
			$PageVisit,
			empty( $where ) ? [ PVFs::ID => $PageVisit->getId() ] : $where,
			$order );
	}

	/**
	 * At the moment there is never a case for updating
	 *
	 * @param ModelInterface $PageVisit
	 * @param array          $values
	 * @param array          $where
	 *
	 * @return ModelInterface
	 */
	public function update( ModelInterface $PageVisit, array $values = [], array $where = [] ): ModelInterface {
		return $PageVisit;
	}

	/**
	 * This is data we will not want to delete
	 *
	 * @param ModelInterface $PageVisit
	 * @param array          $where
	 *
	 * @return ModelInterface
	 */
	public function delete( ModelInterface $PageVisit, array $where = [] ): ModelInterface {
		return $PageVisit;
	}
}