<?php

declare( strict_types = 1 );

namespace App\Controller;

use Exception;
use App\Functions as F;
use App\Model\Helper\PageVisitHelper;
use App\Model\{Login, PageVisit, User};
use Laminas\Form\Form;
use Laminas\Http\Response;
use Laminas\Db\Adapter\Adapter;
use Laminas\Validator\Identical;
use Laminas\Session\SessionManager;
use Laminas\Mvc\Plugin\Identity\Identity;
use Laminas\Authentication\AuthenticationService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\Authentication\Adapter\{AbstractAdapter, AdapterInterface};
use Laminas\Form\FormElementManager\FormElementManagerV3Polyfill as FormManager;

class AbstractController extends AbstractActionController {
	/** @var Adapter */
	protected Adapter $db;
	/** @var Identity */
	protected Identity $Identity;
	/** @var FormManager */
	protected FormManager $FormManager;
	/** @var SessionManager */
	protected SessionManager $SessionManager;
	/** @var FlashMessenger */
	protected FlashMessenger $FlashMessenger;
	/** @var PageVisitHelper */
	protected PageVisitHelper $PageVisitHelper;
	/** @var AuthenticationService */
	protected AuthenticationService $AuthenticationService;

	/**
	 * @param Adapter        $db
	 * @param FormManager    $FormManager
	 * @param SessionManager $SessionManager
	 */
	public function __construct( Adapter $db, FormManager $FormManager, SessionManager $SessionManager ) {
		$this->db              = $db;
		$this->FormManager     = $FormManager;
		$this->SessionManager  = $SessionManager;
		$this->PageVisitHelper = new PageVisitHelper( $this->db );
	}

	/**
	 * @param string $name
	 * @param string $password
	 *
	 * @return self
	 * @throws Exception
	 */
	protected function authenticateLogin( string $name, string $password ): self {
		$Result = $this->getAuthenticationService()->authenticate( $this->getAuthenticationAdapter()->setIdentity( $name )->setCredential( $password ) );
		if( !$Result->isValid() ) {
			throw new Exception( implode( '', $Result->getMessages() ) );
		}

		return $this;
	}

	/** @return Login */
	protected function createLogin(): Login {
		return new Login( $this->getIpAddress(), $this->getUserAgent() );
	}

	/**
	 * @param string $page
	 *
	 * @return PageVisit
	 */
	protected function createPageVisit( string $page ): PageVisit {
		return new PageVisit( $page, $this->getIpAddress(), $this->getUserAgent() );
	}

	/** @return AbstractAdapter */
	protected function getAuthenticationAdapter(): AdapterInterface {
		return $this->getAuthenticationService()->getAdapter();
	}

	/** @return AuthenticationService */
	protected function getAuthenticationService(): AuthenticationService {
		if( empty( $this->AuthenticationService ) ) {
			$this->AuthenticationService = $this->getIdentity()->getAuthenticationService();
		}

		return $this->AuthenticationService;
	}

	/** @return Identity */
	protected function getIdentity(): Identity {
		if( empty( $this->Identity ) ) {
			$this->Identity = $this->plugin( 'identity' );
		}

		return $this->Identity;
	}

	/** @return FlashMessenger */
	protected function getFlashMessenger(): FlashMessenger {
		if( empty( $this->FlashMessenger ) ) {
			$this->FlashMessenger = $this->plugin( 'flashMessenger' );
		}

		return $this->FlashMessenger;
	}

	/**
	 * @param string $formClass
	 *
	 * @return Form
	 */
	protected function getForm( string $formClass ): object {
		return $this->FormManager->get( $formClass );
	}

	/** @return string */
	protected function getIpAddress(): string {
		return $_SERVER[ 'REMOTE_ADDR' ];
	}

	/** @return string */
	protected function getUserAgent(): string {
		return $_SERVER[ 'HTTP_USER_AGENT' ];
	}

	/**
	 * @param string $left
	 * @param string $right
	 * @param string $errorMsg
	 *
	 * @return self
	 * @throws Exception
	 */
	protected function assertIdentical( string $left, string $right, string $errorMsg = 'Passwords are not identical!' ): self {
		if( !( new Identical( $left ) )->isValid( $right ) ) {
			throw new Exception( $errorMsg );
		}

		return $this;
	}

	/**
	 * @param string $left
	 * @param string $right
	 * @param string $errorMsg
	 *
	 * @return self
	 * @throws Exception
	 */
	protected function assertNotIdentical( string $left, string $right, string $errorMsg = 'Fields are identical!' ): self {
		if( ( new Identical( $left ) )->isValid( $right ) ) {
			throw new Exception( $errorMsg );
		}

		return $this;
	}

	/**
	 * @param string $errorMsg
	 *
	 * @return self
	 * @throws Exception
	 */
	protected function assertLoggedIn( string $errorMsg = 'You must be logged in to perform that action.' ): self {
		if( !$this->getAuthenticationService()->hasIdentity() ) {
			throw new Exception( $errorMsg );
		}

		return $this;
	}

	/**
	 * @param string $errorMsg
	 *
	 * @return self
	 * @throws Exception
	 */
	protected function assertLoggedOut( string $errorMsg = 'You are already logged in.' ): self {
		if( $this->getAuthenticationService()->hasIdentity() ) {
			throw new Exception( $errorMsg );
		}

		return $this;
	}

	/**
	 * @param string $password
	 *
	 * @return self
	 * @throws Exception
	 */
	protected function assertPasswordLength( string $password ): self {
		F::assertPasswordLength( $password );

		return $this;
	}

	/**
	 * @param Form $Form
	 *
	 * @return self
	 * @throws Exception
	 */
	protected function validateForm( Form $Form ): self {
		if( !$Form->setData( $this->getRequest()->getPost() )->isValid() ) {
			throw new Exception( implode( '', $Form->getMessages() ) );
		}

		return $this;
	}

	/**
	 * @param string $username
	 * @param string $password
	 *
	 * @return self
	 * @throws Exception
	 */
	protected function validatePassword( string $username, string $password ): self {
		$Result = $this->getAuthenticationAdapter()->setIdentity( $username )->setCredential( $password )->authenticate();
		if( !$Result->isValid() ) {
			throw new Exception( implode( '', $Result->getMessages() ) );
		}

		return $this;
	}
}