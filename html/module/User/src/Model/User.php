<?php

namespace User\Model;

class User {
	/* @var mixed */
	private $id;
	/* @var string */
	private string $username;
	/* @var string */
	private string $password;
	/* @var mixed */
	private $updated_on;
	/* @var mixed */
	private $created_on;

	/**
	 * @param string $username
	 * @param string $password
	 * @param int|null $id
	 * @param ?string $updated_on
	 * @param ?string $created_on
	 */
	public function __construct( string $username, string $password, $id = null, $updated_on = null, $created_on = null ) {
		$this->username   = $username;
		$this->password   = $password;
		$this->updated_on = $updated_on;
		$this->created_on = $created_on;
		$this->id         = $id;
	}

	/* @return mixed */
	public function getId() {
		return $this->id;
	}

	/* @return string */
	public function getUserName(): string {
		return $this->username;
	}

	/* @return string */
	public function getPassword(): string {
		return $this->password;
	}

	/* @return string */
	public function getUpdatedOn(): string {
		return $this->updated_on;
	}

	/* @return string */
	public function getCreatedOn(): string {
		return $this->created_on;
	}

	/**
	 * @param mixed $id
	 * @return User
	 */
	public function setId( $id ): self {
		$this->id = $id;
		return $this;
	}

	/**
	 * @param string $username
	 * @return User
	 */
	public function setUsername( string $username ): self {
		$this->username = $username;
		return $this;
	}

	/**
	 * @param string $password
	 * @return User
	 */
	public function setPassword( string $password ): self {
		$this->password = $password;
		return $this;
	}

	/**
	 * @param string $password
	 * @return string
	 */
	static public function hashPassword( string $password ): string {
		return password_hash( $password, PASSWORD_DEFAULT );
	}

	/**
	 * @param string $hash
	 * @param string $password
	 * @return bool
	 */
	static public function verifyPassword( string $hash, string $password ): bool {
		return password_verify( $password, $hash );
	}
}