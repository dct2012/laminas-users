<?php

declare( strict_types = 1 );

namespace App\Model\Traits;

use App\Model\Identity;

trait TraitIdentityComposite {
	/** @var Identity */
	protected Identity $Identity;
	/** @var ?int */
	protected ?int $identity_id;

	/** @return Identity */
	public function getIdentity(): Identity {
		return $this->Identity;
	}

	/** @return int */
	public function getIdentityId(): int {
		if( empty( $this->identity_id ) ) {
			$this->identity_id = $this->Identity->getId();
		}

		return $this->identity_id;
	}

	/** @return string */
	public function getName(): string {
		return $this->Identity->getName();
	}

	/** @return string */
	public function getPassword(): string {
		return $this->Identity->getPassword();
	}

	/** @return ?string */
	public function getUpdatedOn(): ?string {
		return $this->Identity->getUpdatedOn();
	}

	/** @return string */
	public function getCreatedOn(): string {
		return $this->Identity->getCreatedOn();
	}

	/**
	 * @param Identity $Identity
	 *
	 * @return self
	 */
	public function setIdentity( Identity $Identity ): self {
		$this->Identity = $Identity;

		return $this;
	}

	/**
	 * @param int $id
	 *
	 * @return self
	 */
	public function setIdentityId( int $id ): self {
		$this->Identity->setId( $id );

		return $this;
	}

	/**
	 * @param string $name
	 *
	 * @return self
	 */
	public function setName( string $name ): self {
		$this->Identity->setName( $name );

		return $this;
	}

	/**
	 * @param string $password
	 *
	 * @return self
	 */
	public function setPassword( string $password ): self {
		$this->Identity->setPassword( $password );

		return $this;
	}

	/**
	 * @param string $updatedOn
	 *
	 * @return self
	 */
	public function setUpdatedOn( string $updatedOn ): self {
		$this->Identity->setUpdatedOn( $updatedOn );

		return $this;
	}

	/**
	 * @param string $createdOn
	 *
	 * @return self
	 */
	public function setCreatedOn( string $createdOn ): self {
		$this->Identity->setCreatedOn( $createdOn );

		return $this;
	}
}