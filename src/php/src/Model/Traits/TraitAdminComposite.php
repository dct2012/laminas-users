<?php

declare( strict_types = 1 );

namespace App\Model\Traits;

use App\Model\Admin;

trait TraitAdminComposite {
	/** @var Admin */
	protected Admin $Admin;
	/** @var ?int */
	protected ?int $admin_id;

	/** @return Admin */
	public function getAdmin(): Admin {
		return $this->Admin;
	}

	/** @return ?int */
	public function getAdminId(): ?int {
		if( empty( $this->admin_id ) ) {
			$this->admin_id = $this->Admin->getId();
		}

		return $this->admin_id;
	}

	/** @return string */
	public function getName(): string {
		return $this->Admin->getName();
	}

	/** @return string */
	public function getPassword(): string {
		return $this->Admin->getPassword();
	}

	/** @return string */
	public function getUpdatedOn(): string {
		return $this->Admin->getUpdatedOn();
	}

	/** @return string */
	public function getCreatedOn(): string {
		return $this->Admin->getCreatedOn();
	}

	/**
	 * @param Admin $Admin
	 *
	 * @return self
	 */
	public function setAdmin( Admin $Admin ): self {
		$this->Admin = $Admin;

		return $this;
	}

	/**
	 * @param int $id
	 *
	 * @return self
	 */
	public function setAdminId( int $id ): self {
		$this->Admin->setId( $id );
		$this->admin_id = $id;

		return $this;
	}

	/**
	 * @param string $name
	 *
	 * @return self
	 */
	public function setName( string $name ): self {
		$this->Admin->setName( $name );

		return $this;
	}

	/**
	 * @param string $password
	 *
	 * @return self
	 */
	public function setPassword( string $password ): self {
		$this->Admin->setPassword( $password );

		return $this;
	}

	/**
	 * @param string $updatedOn
	 *
	 * @return self
	 */
	public function setUpdatedOn( string $updatedOn ): self {
		$this->Admin->setUpdatedOn( $updatedOn );

		return $this;
	}

	/**
	 * @param string $createdOn
	 *
	 * @return self
	 */
	public function setCreatedOn( string $createdOn ): self {
		$this->Admin->setCreatedOn( $createdOn );

		return $this;
	}
}