<?php

declare( strict_types = 1 );

namespace App\Model\Traits;

use App\Model\User;

trait TraitUserComposite {
	/** @var User */
	protected User $User;
	/** @var ?int */
	protected ?int $user_id;

	/** @return User */
	public function getUser(): User {
		return $this->User;
	}

	/** @return int */
	public function getUserId(): int {
		if( empty( $this->user_id ) ) {
			$this->user_id = $this->User->getId();
		}

		return $this->user_id;
	}

	/** @return string */
	public function getName(): string {
		return $this->User->getName();
	}

	/** @return string */
	public function getPassword(): string {
		return $this->User->getPassword();
	}

	/** @return string */
	public function getUpdatedOn(): string {
		return $this->User->getUpdatedOn();
	}

	/** @return string */
	public function getCreatedOn(): string {
		return $this->User->getCreatedOn();
	}

	/**
	 * @param User $User
	 *
	 * @return self
	 */
	public function setUser( User $User ): self {
		$this->User = $User;

		return $this;
	}

	/**
	 * @param int $id
	 *
	 * @return self
	 */
	public function setUserId( int $id ): self {
		$this->User->setId( $id );
		$this->user_id = $id;

		return $this;
	}

	/**
	 * @param string $name
	 *
	 * @return self
	 */
	public function setName( string $name ): self {
		$this->User->setName( $name );

		return $this;
	}

	/**
	 * @param string $password
	 *
	 * @return self
	 */
	public function setPassword( string $password ): self {
		$this->User->setPassword( $password );

		return $this;
	}

	/**
	 * @param string $updatedOn
	 *
	 * @return self
	 */
	public function setUpdatedOn( string $updatedOn ): self {
		$this->User->setUpdatedOn( $updatedOn );

		return $this;
	}

	/**
	 * @param string $createdOn
	 *
	 * @return self
	 */
	public function setCreatedOn( string $createdOn ): self {
		$this->User->setCreatedOn( $createdOn );

		return $this;
	}
}