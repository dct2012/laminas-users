<?php

namespace User\Model;

class User {
	/* @var mixed */
	private $id;
	/* @var string */
	private string $username;
	/* @var string */
	private string $password;

	/**
	 * @param string $username
	 * @param string $password
	 * @param int|null $id
	 */
	public function __construct( string $username, string $password, $id = null ) {
		$this->username = $username;
		$this->password = $password;
		$this->id       = $id;
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